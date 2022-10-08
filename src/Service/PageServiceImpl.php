<?php

namespace App\Service;

use App\Exception\ApplicationException;
use App\Repository\PageRepository;
use Doctrine\DBAL\Exception;

class PageServiceImpl implements PageService
{
    private PageRepository $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @throws Exception|ApplicationException
     */
    public function createPages(array $pages): void
    {
        if(empty($pages)){
            throw new ApplicationException('Cannot create pages from empty payload');
        }

        $this->pageRepository->insert($pages);
    }
}
