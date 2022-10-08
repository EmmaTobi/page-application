<?php

namespace App\Service;

use App\Exception\ApplicationException;

interface FileService
{
    /**
     * @throws ApplicationException
     */
    public function batchProcessCsv(
        int $batchCount,
        string $path,
        \Closure $closure
    ): void ;
}
