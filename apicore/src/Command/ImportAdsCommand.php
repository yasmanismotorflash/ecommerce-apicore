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
    private $apiMFClient;


    public function __construct( APIMFClient $apiMFClient)
    {
        parent::__construct();
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
        $io = new SymfonyStyle($input, $output);

        $output->writeln('Iniciando ejecución de comando de APIMF Client ...');

        //$this->apiMFClient->dumpConfig();

        $resp = $this->apiMFClient->getAdsByShop(992);
        $respArray = json_decode($resp,true);
        $ads = $respArray['advertisements'];

        foreach ( $ads as $ad){

            $dealer = DealerBuilder::buildFromArray($ad['dealer']);  // ToDo: Procesar el dealer, verificar si existe en bd primero sino lo crea
            $shop = ShopBuilder::buildFromArray($ad['shop']);        //  ToDo: Procesar  la tienda, verificar si existe en bd primero sino lo crea
            $images = $ad['images'];                            // ToDo: Procesar la lista de imagenes
            $advertisement = AdvertisementBuilder::buildFromArray($ad);

            $output->writeln('Obtenido Anuncio: '.$ad['id']." Dealer: ".$dealer->getMfid()."  Shop: ".$shop->getMfid(). " Imágenes: ".count($images));
        }

        $output->writeln('Procesados '.count($ads).' anuncios !');
        $output->writeln('Ejecución completada !');
        return Command::SUCCESS;
    }
}
