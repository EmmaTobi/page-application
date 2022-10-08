<?php

namespace App\Dto;

use App\Entity\Page;

class PageDto
{
    public static function fromArray(array $pageItem): Page
    {
        $page =  new Page();

        $page->setUrl($pageItem["url"] ?? "");
        $page->setCreatedAt(new \DateTimeImmutable());

        return $page;
    }
}
