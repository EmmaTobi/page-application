<?php

namespace App\Importer;

use App\Exception\ApplicationException;
use App\Service\FileService;
use App\Service\PageCsvService;
use App\Service\PageService;

class PageCsvImporter
{
    public const BATCH = 1000;

    private PageCsvService $pageCsvService;
    private PageService $pageService;
    private FileService $fileService;

    public function __construct(
        FileService $fileService,
        PageCsvService $pageCsvService,
        PageService $pageService
    ) {
        $this->fileService = $fileService;
        $this->pageCsvService = $pageCsvService;
        $this->pageService = $pageService;
    }

    /**
     * @throws ApplicationException
     */
    public function import(string $csvFilePath): void {
        $this->fileService->batchProcessCsv(self::BATCH, $csvFilePath, function (&$csvContent){
            $pages = $this->pageCsvService->parsePagesFromCsvContent($csvContent);
            // clear csv content to free up memory
            $csvContent = [];
            $this->pageService->createPages($pages);
        });
    }
}
