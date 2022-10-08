<?php

namespace App\Tests\Unit\Service;

use App\Entity\Page;
use App\Exception\ApplicationException;
use App\Service\PageCsvServiceImpl;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PageCsvServiceTest extends KernelTestCase
{
    private PageCsvServiceImpl $pageCsvService;

    public function setUp() : void {
        self::bootKernel();

        $container = static::getContainer();

        $this->pageCsvService = $container->get(PageCsvServiceImpl::class);
    }

    /**
     * @throws ApplicationException
     */
    public function testParsePagesFromCsvContent_Successful(): void {
        // GIVEN
        $payload = [
            [
                'url' => 'http://localhost'
            ]
        ];

        $pages = $this->pageCsvService->parsePagesFromCsvContent($payload);

        // TEST
        $this->assertCount(count($payload), $pages);
        $this->assertEquals(Page::class, get_class($pages[0]));
    }

    /**
     * @throws ApplicationException
     */
    public function testParsePagesFromCsvContent_ThrowsApplicationException_GivenMalformedPayload(): void {
        // TEST
        $this->expectException(ApplicationException::class);

        $this->pageCsvService->parsePagesFromCsvContent([
            'urd' => 'dummy'
        ]);
    }

    /**
     * @throws ApplicationException
     */
    public function testParsePagesFromCsvContent_Successful_InvalidUrlIsSkipped(): void {
        // GIVEN
        $csvContent = [
            [
                'url' => 'http://localhost'
            ],
            [
                'url' => 'dummy'
            ],
        ];

        $expectedPagesCount = 1;
        $pages = $this->pageCsvService->parsePagesFromCsvContent($csvContent);
        // TEST
        $this->assertCount($expectedPagesCount, $pages);
    }
}
