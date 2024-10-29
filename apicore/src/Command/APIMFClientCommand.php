<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Services\MFServices\APIMFClient;
#[AsCommand(
    name: 'apimf:client',
    description: 'Add a short description for your command',
)]
class APIMFClientCommand extends Command
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

        $resp = $this->apiMFClient->getAdsByMfId(59122140);

        var_dump($resp);

        $output->writeln('Ejecución completada !');
        return Command::SUCCESS;
    }
}
