<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Services\Import\Motorflash\APIMF\APIMFClient;
use App\Services\Import\Motorflash\APIMF\Transform\AdvertisementBuilder;
use App\Services\Import\Motorflash\APIMF\Transform\DealerBuilder;
use App\Services\Import\Motorflash\APIMF\Transform\ShopBuilder;

#[AsCommand(name: 'apicore:import:ads', description: 'Importar anuncios de clientes')]
class ImportAdsCommand extends Command
{
    private bool $debug;

    private bool $dryrun;

    private $output;

    private APIMFClient $apiMFClient;


    public function __construct( APIMFClient $apiMFClient)
    {
        parent::__construct();
        $this->debug = boolval($_ENV['APP_DEBUG']);
        $this->dryrun = false;
        $this->apiMFClient = $apiMFClient;
    }

    protected function configure(): void
    {
        //$this
        //    ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //    ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        //;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //$io = new SymfonyStyle($input, $output);

        $output->writeln('Iniciando ejecución de comando de importación desde APIMF ...');


        //Obtener clientes desde bd
        $clients = ' obtener clientes activos';  // ToDo: Implementar obtener clientes desde bd

        //Iterar sobre los clientes para importar los anuncios, dealers y shops
        foreach ($clients as $client){

            //Obtener id y secrest y setear a cliente de apimf
            $this->apiMFClient->setApiMfClientId($client->getApiMfClientId());
            $this->apiMFClient->setApiMfClientSecret($client->getApiMfClientSecret());

            //Autenticarse en APIMF para acceder a los datos del cliente.
            $this->apiMFClient->authenticate();

            //pedir los anuncios del cliente solicitado
            $resp = $this->apiMFClient->getAdsByPage(40,1);          //byShop(992);

            $respArray = json_decode($resp,true);
            if (json_last_error() !== JSON_ERROR_NONE){
                $output->writeln('Error decodificando JSON de respuestqa de APIMF');
                return Command::FAILURE;
            }


            $ads = $respArray['advertisements'];

            foreach ( $ads as $ad){

                $dealer = DealerBuilder::buildFromArray($ad['dealer']);  // ToDo: Procesar el dealer, verificar si existe en bd primero sino lo crea
                $shop = ShopBuilder::buildFromArray($ad['shop']);        // ToDo: Procesar  la tienda, verificar si existe en bd primero sino lo crea
                $images = $ad['images'];                                 // ToDo: Procesar la lista de imágenes
                $advertisement = AdvertisementBuilder::buildFromArray($ad); // ToDo: Procesar el anuncio y persistirlo en bd

                $output->writeln('Obtenido Anuncio: '.$ad['id']." Dealer: ".$dealer->getMfid()."  Shop: ".$shop->getMfid(). " Imágenes: ".count($images));
            }
        }


        //Mostrar resumen

        $output->writeln('Procesados '.count($ads).' anuncios !');
        $output->writeln('Ejecución completada !');
        return Command::SUCCESS;
    }
}
