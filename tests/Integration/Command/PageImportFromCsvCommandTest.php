<?php

namespace App\Integration\Command;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PageImportFromCsvCommandTest extends KernelTestCase
{
    public const CSV_FILE_NAME_CONTAINING_10_PAGES = '10pages.csv';
    public const DUMMY_CSV_FILE = 'dummycsvfile.csv';
    public const IMPORT_FROM_CSV_COMMAND_SIGNATURE = 'app:page:import-from-csv';

    private PageRepository $pageRepository;

    private CommandTester $commandTester;

    private string $baseResourcePath;

    public function setup(): void
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();

        $application = new Application($kernel);

        $command = $application->find(self::IMPORT_FROM_CSV_COMMAND_SIGNATURE);
        $this->commandTester = new CommandTester($command);

        $this->pageRepository = $container->get(PageRepository::class);

        $params = $container->get(ParameterBagInterface::class);
        $this->baseResourcePath = $params->get("test.baseresourcepath");
    }

    public function testImportFromCsvCommand_Successful(): void
    {
        $this->commandTester->execute([
            'file' => sprintf('%s/%s', $this->baseResourcePath, self::CSV_FILE_NAME_CONTAINING_10_PAGES),
        ]);

        // TEST
        $this->commandTester->assertCommandIsSuccessful();

        $expectedImportedPages = 10;
        // TEST
        $this->assertEquals($expectedImportedPages, $this->pageRepository->countAll());
    }

    public function testImportFromCsvCommand_WithInvalidCsvFile_Unsuccessful(): void
    {
        $exitCode = $this->commandTester->execute([
            'file' => sprintf('%s/%s', $this->baseResourcePath, self::DUMMY_CSV_FILE),
        ]);

        // TEST
        $this->assertEquals(Command::FAILURE, $exitCode);

        $expectedImportedPages = 0;
        // TEST
        $this->assertEquals($expectedImportedPages, $this->pageRepository->countAll());
    }
}
