<?php
namespace App\Command;

use App\Services\Import\Motorflash\APIMF\APIMFClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'apicore:import:ads',
    description: 'Add a short description for your command',
)]
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

        $this->apiMFClient->dumpConfig();

        $resp = $this->apiMFClient->getAdsByShop(992);
        $respArray = json_decode($resp,true);
        $ads = $respArray['advertisements'];

        /* "httpCode": "200",
           "httpMessage": "Ok",
           "total": "140",
           "page": null,
           "perPage": null,
           "pages": 0,
           "advertisements"
         */

        foreach ( $ads as $ad){
            $output->writeln('Anuncio: '.$ad['id']);

        }


        $output->writeln('Ejecución completada !');
        return Command::SUCCESS;
    }
}