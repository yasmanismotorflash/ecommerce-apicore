<?php
namespace App\Command;

use App\Entity\Advertisement;
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

use App\Entity\Make;
use App\Entity\Model;
use App\Entity\Version;

use App\Services\Import\Motorflash\APIMF\APIMFClient;
use App\Services\Import\Motorflash\APIMF\Transform\AdvertisementBuilder;
use App\Services\Import\Motorflash\APIMF\Transform\DealerBuilder;
use App\Services\Import\Motorflash\APIMF\Transform\ShopBuilder;
use App\Services\Import\Motorflash\APIMF\Transform\ImageBuilder;
use App\Services\Import\Motorflash\APIMF\Transform\VideoBuilder;


#[AsCommand(name: 'apicore:import:ads', description: 'Importar anuncios de sitios')]
class ImportAdsCommand extends Command
{
    private EntityManagerInterface $em;
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
        $this->em = $entityManager;
        $this->apiMFClient = $apiMFClient;
        $this->debug = boolval($_ENV['APP_DEBUG']);
        $this->dryrun = false;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Importa anuncios desde APIMF para sitios activos, parámetros opcionales  dryrun, siteMfId, pageSize, pagesCount')
            ->addOption('dryrun', null, InputOption::VALUE_NONE,'Si se especifica, el comando ejecutará en modo de prueba sin persistir datos en la base de datos')
            ->addOption('siteMfId', null, InputOption::VALUE_OPTIONAL,'Especifica el mfid de un sitio en particular para importar solo sus anuncios', null)
            ->addOption('pageSize', null, InputOption::VALUE_OPTIONAL,'Número de anuncios a traer por página', 40)
            ->addOption('pagesCount', null, InputOption::VALUE_OPTIONAL,'Número de página a procesar', 0);
    }




    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->dryrun = $input->getOption('dryrun'); // Activa modo de prueba
        $pageSize = $input->getOption('pageSize'); // Define el tamaño de la página
        $pagesCount = $input->getOption('pagesCount'); // Define la cantidad de paginas a procesar
        $sitemfid = $input->getOption('siteMfId'); // Selecciona un cliente específico

        $message ='Inicio de importación de anuncios desde APIMF';
        $this->io->title($message); $this->log->info($message);

        // Filtrar sites según el mfsisteid, si se ha especificado o buscar los activos
        $sites = $sitemfid ? [$this->em->getRepository(Site::class)->findOneBymfSiteId($sitemfid)] : $this->em->getRepository(Site::class)->findBy(['active' => true]);

        if (count($sites)==0) {
            $message = 'No se encontraron clientes activos.';
            $this->io->warning($message); $this->log->info($message);
            return Command::SUCCESS;
        }

        foreach ($sites as $site) {
            $this->processSiteAds($site,$pageSize,$pagesCount);
        }

        $this->em->flush();
        $this->io->success('Ejecución completada correctamente.');

        return Command::SUCCESS;
    }


    private function processSiteAds(Site $site, $pageSize = 40, $pagesCount = 0): void
    {
        ini_set('memory_limit', '-1');


        // Configurar inicializar autenticación de apimfclient para el sitio especificado
        $this->initializeAPIMFClient($site);
        $this->io->section("Obteniendo anuncios de sitio: {$site->getName()} ...");
        $page = 1;
        do {
            $response = $this->apiMFClient->getAdsByPage($pageSize, $page);
            $responseData = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $message = 'Error al decodificar JSON de respuesta de APIMF.';
                $this->io->error($message); $this->log->error($message);
                return;
            }
            $this->io->text("Procesando anuncios ...");
            $page = intval($responseData['page']);
            $pages = intval($responseData['pages']);

            $ads = $responseData['advertisements'] ?? [];

            foreach ($ads as $ad) {
                gc_enable();
                try {
                    /*** @var Advertisement $advertisement */
                    $advertisement =  AdvertisementBuilder::getAdvertisement($this->em,$site,$ad);
                    if(!$advertisement){
                        // ToDo: Registrar en log que se obtuvo un dato con problema y saltar
                        continue;
                    }

                    // Si no es dryrun, persistimos y guardamos los cambios.
                    if (!$this->dryrun && $advertisement) {
                        $this->em->flush();
                    }

                    $this->io->text(" > Procesado Anuncio ID : ".$advertisement->getMfid());
                }
                catch (Exception $e) {
                    $this->io->error(" > Error procesando anuncio : ".$e->getMessage());
                    $this->io->error(" > Traza del error : ".$e->getTrace());
                    continue;
                }
                gc_collect_cycles();
            }
            $this->io->section("Procesada página: " . $page . " de " . $pages);

            if($pagesCount != 0 && $pagesCount == $page){
                $this->io->success("No se procesan más páginas, se llegó a la cantidad de paginas especificadas para obtener.  pagesCount = ".$page);
                break;
            }
            $page++;
        } while ($page <= $pages);
        $this->io->success("Procesados " . count($ads) . " anuncios para el sitio {$site->getName()}.");
    }

    /**
     * @param Site $site
     * @return void
     */
    public function initializeAPIMFClient(Site $site): void
    {
        $this->apiMFClient->setApiMfClientId($site->getApiMfClientId());
        $this->apiMFClient->setApiMfClientSecret($site->getApiMfClientSecret());
        $this->apiMFClient->authenticate();
    }

}
