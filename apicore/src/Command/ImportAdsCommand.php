<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Dealer;
use App\Entity\Ecommerce;
use App\Entity\Shop;
use App\Services\Import\Motorflash\APIMF\APIMFClient;
use App\Services\Import\Motorflash\APIMF\Transform\AdvertisementBuilder;
use App\Services\Import\Motorflash\APIMF\Transform\DealerBuilder;
use App\Services\Import\Motorflash\APIMF\Transform\ShopBuilder;

#[AsCommand(name: 'apicore:import:ads', description: 'Importar anuncios de clientes')]
class ImportAdsCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private APIMFClient $apiMFClient;
    private bool $debug;
    private bool $dryrun;
    private SymfonyStyle $io;

    public function __construct(EntityManagerInterface $entityManager, APIMFClient $apiMFClient)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->apiMFClient = $apiMFClient;
        $this->debug = boolval($_ENV['APP_DEBUG']);
        $this->dryrun = false;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Importa anuncios desde APIMF para clientes activos')

            ->addOption('dryrun',null,InputOption::VALUE_NONE,
                'Si se especifica, el comando ejecutará en modo de prueba sin persistir datos en la base de datos')

            ->addOption('pageLimit',null,InputOption::VALUE_OPTIONAL,
                'Número de anuncios a traer por página en la importación',40)

            ->addOption('clientId',null,InputOption::VALUE_OPTIONAL,
                'Especifica el ID de un cliente en particular para importar solo sus anuncios',null);
    }




    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->dryrun = $input->getOption('dryrun'); // Activa modo de prueba
        $pageLimit = $input->getOption('pageLimit'); // Define el límite por página
        $clientId = $input->getOption('clientId'); // Selecciona un cliente específico

        $this->io->title('Inicio de importación de anuncios desde APIMF');

        // Filtrar clientes según el clientId, si se ha especificado o buscar los activos
        $clients = $clientId?
            [$this->entityManager->getRepository(Ecommerce::class)->find($clientId)]:
            $this->entityManager->getRepository(Ecommerce::class)->findBy(['active' => true]);

        if (!$clients) {
            $this->io->warning('No se encontraron clientes activos.');
            return Command::SUCCESS;
        }

        foreach ($clients as $client) {
            $this->processClientAds($client);
        }

        $this->entityManager->flush();
        $this->io->success('Ejecución completada correctamente.');
        return Command::SUCCESS;
    }



    private function processClientAds(Ecommerce $client): void
    {
        $this->io->section("Procesando cliente: {$client->getName()}");

        // Configurar autenticación para el cliente actual
        $this->apiMFClient->setApiMfClientId($client->getApiMfClientId());
        $this->apiMFClient->setApiMfClientSecret($client->getApiMfClientSecret());
        $this->apiMFClient->authenticate();

        // Obtener anuncios del cliente
        $response = $this->apiMFClient->getAdsByPage(40, 1);

        // ToDO: Implementar la logica para procesar todas las páginas

        $responseData = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->io->error('Error al decodificar JSON de respuesta de APIMF.');
            return;
        }

        $ads = $responseData['advertisements'] ?? [];

        foreach ($ads as $ad) {
            $advertisement = AdvertisementBuilder::buildFromArray($ad);

            // Asociar Dealer
            $dealer = $this->getOrCreateDealer($ad['dealer']);
            $advertisement->setDealer($dealer);

            // Asociar Shop
            $shop = $this->getOrCreateShop($ad['shop']);
            $advertisement->setShop($shop);

            // Si no es dryrun, persistimos
            if (!$this->dryrun) {
                $this->entityManager->persist($advertisement);
            }

            $this->io->text("Anuncio ID {$ad['id']} - Dealer: {$dealer->getMfid()}, Shop: {$shop->getMfid()}");
        }

        $this->io->success("Procesados ".count($ads)." anuncios para el cliente {$client->getName()}.");
    }


    /**
     * Obtener un ojeto Dealer
     * */
    private function getOrCreateDealer(array $dealerData): Dealer
    {
        $dealer = DealerBuilder::buildFromArray($dealerData);
        $existingDealer = $this->entityManager->getRepository(Dealer::class)->findOneBy(['mfid' => $dealer->getMfid()]);

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
        $existingShop = $this->entityManager->getRepository(Shop::class)->findOneBy(['mfid' => $shop->getMfid()]);

        if (!$existingShop && !$this->dryrun) {
            $this->entityManager->persist($shop);
            return $shop;
        }
        return $existingShop ?: $shop;
    }

   /* private function processImages(array $imagesData): array
    {
        $images = [];
        foreach ($imagesData as $imageData) {
            $image = ImageBuilder::buildFromArray($imageData);
            $this->entityManager->persist($image);
            $images[] = $image;
        }
        return $images;
    }*/

}
