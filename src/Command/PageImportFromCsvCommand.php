<?php

namespace App\Command;

use App\Importer\PageCsvImporter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:page:import-from-csv',
    description: 'Import page from csv file',
)]
class PageImportFromCsvCommand extends Command
{
    protected PageCsvImporter $pageCsvImporter;

    public function __construct(PageCsvImporter $pageCsvImporter)
    {
        parent::__construct();

        $this->pageCsvImporter = $pageCsvImporter;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Csv File Path')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $csvFilePath = $input->getArgument('file');

        $io->info(['Starting page Import ...', 'Duplicate pages would be skipped']);

        try{
            $this->pageCsvImporter->import($csvFilePath);
        }catch (\Exception $exception){
            $io->warning(['Page import unsuccessful', $exception->getMessage()]);

            return Command::FAILURE;
        }

        $io->success('Page Imported Successfully');

        return Command::SUCCESS;
    }
}
