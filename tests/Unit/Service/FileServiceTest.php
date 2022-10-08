<?php

namespace App\Tests\Service;

use App\Exception\ApplicationException;
use App\Service\FileService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileServiceTest extends KernelTestCase
{
    const CSV_FILE_NAME_CONTAINING_10_PAGES = '10pages.csv';

    private FileService $fileService;

    private string $baseResourcePath;

    /**
     * @throws Exception
     */
    public function setUp() : void {
        self::bootKernel();

        $container = static::getContainer();

        $this->fileService = $container->get(FileService::class);

        $params = $container->get(ParameterBagInterface::class);
        $this->baseResourcePath = $params->get("test.baseresourcepath");
    }

    /**
     * @throws ApplicationException
     */
    public function testBatchProcessCsv_ReturnsExpectedCsvContent(): void {
        // GIVEN
        $filePath = sprintf("%s/%s", $this->baseResourcePath, self::CSV_FILE_NAME_CONTAINING_10_PAGES);

        $batchCount = 11;
        $expectedCsvContentCount = 10;

        $this->fileService->batchProcessCsv(
                                    $batchCount,
                                    $filePath,
                                    function (array $csvContent) use ($expectedCsvContentCount) {
                                        // TEST
                                        $this->assertCount($expectedCsvContentCount, $csvContent);
                                    }
                         );
    }

    /**
     * @throws ApplicationException
     */
    public function testBatchProcessCsv_ExpectsBatchProcessing(): void {
        // GIVEN
        $filePath = sprintf("%s/%s", $this->baseResourcePath, self::CSV_FILE_NAME_CONTAINING_10_PAGES);

        $batch = 2;
        $expectedBatchCount = 6;
        $actualBatchCount = 0;

        $this->fileService->batchProcessCsv(
            $batch,
            $filePath,
            function (array $csvContent) use (&$actualBatchCount) {
                ++$actualBatchCount;
            }
        );

        // TEST
        $this->assertEquals($expectedBatchCount, $actualBatchCount);
    }
}
