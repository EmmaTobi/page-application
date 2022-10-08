<?php

namespace App\Service;

interface PageCsvService {

    public function parsePagesFromCsvContent(
        array $csvContent
    ): array;
}

