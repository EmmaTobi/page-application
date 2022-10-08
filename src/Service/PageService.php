<?php

namespace App\Service;

use Doctrine\DBAL\Exception;

interface PageService
{
    /**
     * @throws Exception
     */
    public function createPages(array $pages): void;
}
