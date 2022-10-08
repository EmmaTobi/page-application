<?php

namespace App\Tests\Unit\Service;

use App\Dto\PageDto;
use App\Exception\ApplicationException;
use App\Repository\PageRepository;
use App\Service\PageService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PageServiceTest extends KernelTestCase
{
    private PageService $pageService;

    private PageRepository $pageRepository;

    public function setUp() : void {
        self::bootKernel();

        $container = static::getContainer();

        $this->pageRepository = $this->createMock(PageRepository::class);

        $container->set(PageRepository::class, $this->pageRepository);

        $this->pageService = $container->get(PageService::class);
        $this->pageRepository = $container->get(PageRepository::class);
    }

    /**
     * @throws Exception
     */
    public function testCreatePages(): void
    {
        // TEST
        $this->pageRepository->expects($this->once())
            ->method('insert')
            ->with($this->isType('array'));

        $this->pageService->createPages($this->getPages());
    }

    /**
     * @throws Exception
     */
    public function testCreatePages_ThrowsApplicationException_withEmptyPayload(): void
    {
        // TEST
        $this->pageRepository->expects($this->never())
            ->method('insert');

        // TEST
        $this->expectException(ApplicationException::class);

        $this->pageService->createPages([]);
    }

    private function getPages(): array {
        return [
            PageDto::fromArray([
                'url' => 'http://localhost'
            ])
        ];
    }
}
