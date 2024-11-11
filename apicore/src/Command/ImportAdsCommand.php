<?php
namespace App\Command;

use App\Services\Comun\SimpleLog;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Dealer;
use App\Entity\Site;
use App\Entity\Shop;
use App\Services\Import\Motorflash\APIMF\APIMFClient;
use App\Services\Import\Motorflash\APIMF\Transform\AdvertisementBuilder;
use App\Services\Import\Motorflash\APIMF\Transform\DealerBuilder;
use App\Services\Import\Motorflash\APIMF\Transform\ShopBuilder;
use App\Services\Import\Motorflash\APIMF\Transform\ImageBuilder;
use App\Services\Import\Motorflash\APIMF\Transform\VideoBuilder;


#[AsCommand(name: 'apicore:import:ads', description: 'Importar anuncios de clientes')]
class ImportAdsCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private APIMFClient $apiMFClient;
    private bool $debug;
    private bool $dryrun;

    private SimpleLog $log;

    private SymfonyStyle $io;




    public function __construct(SimpleLog $log, EntityManagerInterface $entityManager, APIMFClient $apiMFClient)
    {
        parent::__construct();
        $this->log = $log;
        $this->log->configure(true,'cmd-import-ads', true);
        $this->entityManager = $entityManager;
        $this->apiMFClient = $apiMFClient;
        $this->debug = boolval($_ENV['APP_DEBUG']);
        $this->dryrun = false;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Importa anuncios desde APIMF para clientes activos')
            ->addOption('dryrun', null, InputOption::VALUE_NONE,
                'Si se especifica, el comando ejecutará en modo de prueba sin persistir datos en la base de datos')
            ->addOption('pageLimit', null, InputOption::VALUE_OPTIONAL,
                'Número de anuncios a traer por página en la importación', 40)
            ->addOption('clientId', null, InputOption::VALUE_OPTIONAL,
                'Especifica el ID de un cliente en particular para importar solo sus anuncios', null);
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->dryrun = $input->getOption('dryrun'); // Activa modo de prueba
        $pageLimit = $input->getOption('pageLimit'); // Define el límite por página
        $clientId = $input->getOption('clientId'); // Selecciona un cliente específico

        $message ='Inicio de importación de anuncios desde APIMF';
        $this->io->title($message);
        $this->log->info($message);

        // Filtrar clientes según el clientId, si se ha especificado o buscar los activos
        $clients = $clientId ?
            [$this->entityManager->getRepository(Site::class)->find($clientId)] :
            $this->entityManager->getRepository(Site::class)->findBy(['active' => true]);

        if (!$clients) {
            $message = 'No se encontraron clientes activos.';
            $this->io->warning($message);
            $this->log->info($message);
            return Command::SUCCESS;
        }

        foreach ($clients as $client) {
            $this->processClientAds($client);
        }

        $this->entityManager->flush();
        $this->io->success('Ejecución completada correctamente.');

        return Command::SUCCESS;
    }


    private function processClientAds(Site $client): void
    {
        ini_set('memory_limit', '-1');
        $this->io->section("Procesando cliente: {$client->getName()}");

        // Configurar autenticación para el cliente actual
        $this->apiMFClient->setApiMfClientId($client->getApiMfClientId());
        $this->apiMFClient->setApiMfClientSecret($client->getApiMfClientSecret());
        $this->apiMFClient->authenticate();

        $page = 1;
        $pages = 1;
        $perPage = 40;

        do {
            // Obtener anuncios del cliente
            $response = $this->apiMFClient->getAdsByPage($perPage, $page);
            $responseData = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $message = 'Error al decodificar JSON de respuesta de APIMF.';
                $this->io->error($message);
                $this->log->error($message);
                return;
            }

            $page = intval($responseData['page']);
            $pages = intval($responseData['pages']);

            $ads = $responseData['advertisements'] ?? [];

            foreach ($ads as $ad) {
                gc_enable();

                $dealer = $this->getOrCreateDealer($ad['dealer']);
                $shop = $this->getOrCreateShop($ad['shop']);
                $images = $this->processImages($ad['images']);
                $video = VideoBuilder::buildFromString($ad['video']);

                $advertisement = AdvertisementBuilder::buildFromArray($ad);

                if(!$advertisement)
                    continue;

                $advertisement->setDealer($dealer);
                $advertisement->setShop($shop);
                $advertisement->setImages($images);
                $advertisement->setVideo($video);

                $this->entityManager->persist($advertisement);

                // Si no es dryrun, persistimos y guardamos los cambios.
                if (!$this->dryrun) {
                    $this->entityManager->flush();
                }

                $this->io->text("Anuncio ID {$ad['id']} - Dealer: {$dealer->getMfid()}, Shop: {$shop->getMfid()}".", Images: ". count($images) );
                gc_collect_cycles();
            }
            $this->io->text("Procesada página: " . $page . " de " . $pages);
            $page++;
        } while ($page <= $pages);
        $this->io->success("Procesados " . count($ads) . " anuncios para el cliente {$client->getName()}.");
    }


    /**
     * Obtener un objeto Dealer
     * */
    private function getOrCreateDealer(array $dealerData): Dealer
    {
        $dealer = DealerBuilder::buildFromArray($dealerData);
        $existingDealer = $this->entityManager->getRepository(Dealer::class)->findOneByMfid($dealer->getMfid());

        if (!$existingDealer && !$this->dryrun) {
            $this->entityManager->persist($dealer);
            return $dealer;
        }

        return $existingDealer ?: $dealer;
    }

    /**
     * Obtener una Tienda (Shop)
     */
    private function getOrCreateShop(array $shopData): Shop
    {
        $shop = ShopBuilder::buildFromArray($shopData);
        $existingShop = $this->entityManager->getRepository(Shop::class)->findOneByMfid($shop->getMfid());

        if (!$existingShop && !$this->dryrun) {
            $this->entityManager->persist($shop);
            return $shop;
        }
        return $existingShop ?: $shop;
    }

    private function processImages(array $imagesData): array
    {
        $images = [];
        foreach ($imagesData as $url) {
            $image = ImageBuilder::buildFromString($url);
            $images[] = $image;
        }
        return $images;
    }

}
