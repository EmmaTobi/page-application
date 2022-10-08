<?php

namespace App\Integration\Unit\Importer;

use App\Exception\ApplicationException;
use App\Importer\PageCsvImporter;
use App\Repository\PageRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PageCsvImporterTest extends KernelTestCase
{
    const CSV_FILE_NAME_CONTAINING_10_PAGES = '10pages.csv';
    const CSV_FILE_NAME_CONTAINING_1000000_PAGES = '1000000pages.csv';
    const CSV_FILE_NAME_CONTAINING_EMPTY_PAGES = 'empty.csv';

    private PageCsvImporter $pageCsvImporter;

    private PageRepository $pageRepository;

    private string $baseResourcePath;

    /**
     * @throws Exception
     */
    public function setUp() : void {
        self::bootKernel();

        $container = static::getContainer();

        $this->pageCsvImporter = $container->get(PageCsvImporter::class);
        $this->pageRepository = $container->get(PageRepository::class);
        $params = $container->get(ParameterBagInterface::class);

        $this->baseResourcePath = $params->get("test.baseresourcepath");
    }

    /**
     * @throws ApplicationException
     */
    public function testImport_WithCsvFileContaining10Records() : void {
        // TEST
        $filePath = sprintf("%s/%s", $this->baseResourcePath, self::CSV_FILE_NAME_CONTAINING_10_PAGES);
        $this->pageCsvImporter->import($filePath);

        $expectedImportedPages = 10;
        // TEST
        $this->assertEquals($expectedImportedPages, $this->pageRepository->countAll());
    }

    /**
     * This test should only be run if you know why you should run it
     * To run this test change access modifier to public
     * @throws ApplicationException
     */
    private function testImport_WithCsvFileContaining1000000Records() : void {
        // TEST
        $filePath = sprintf("%s/%s", $this->baseResourcePath, self::CSV_FILE_NAME_CONTAINING_1000000_PAGES);
        $this->pageCsvImporter->import($filePath);

        $expectedImportedPages = 1000000;
        // TEST
        $this->assertEquals($expectedImportedPages, $this->pageRepository->countAll());
    }

    /**
     * @throws ApplicationException
     */
    public function testImport_WithEmptyCsvFile() : void {
        // GIVEN
        $filePath = sprintf("%s/%s", $this->baseResourcePath, self::CSV_FILE_NAME_CONTAINING_EMPTY_PAGES);
        $this->pageCsvImporter->import($filePath);

        $expectedImportedPages = 0;
        // TEST
        $this->assertEquals($expectedImportedPages, $this->pageRepository->countAll());
    }
}
