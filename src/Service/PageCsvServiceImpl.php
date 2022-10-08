<?php

namespace App\Service;

use App\Dto\PageDto;
use App\Exception\ApplicationException;
use App\Util\PageCsvValidator;

class PageCsvServiceImpl implements PageCsvService
{
    public const FORMAT = "csv";

    /**
     * @throws ApplicationException
     */
    public function parsePagesFromCsvContent(array $csvContent): array {

        if (count($csvContent) == count($csvContent, COUNT_RECURSIVE)){
            throw new ApplicationException("Invalid csv content format");
        }

        $csvContent = array_unique( $csvContent, SORT_REGULAR );

        $pageCsvValidator = new PageCsvValidator();
        $pageCsvValidator->addValidator(PageCsvValidator::URL_VALIDATOR);


        $csvContent = array_filter($csvContent, function(array $pageItem) use ($pageCsvValidator) {
            return $pageCsvValidator->validate($pageItem);
        });

        if( empty($csvContent) ) {
            throw new ApplicationException("Csv content contains no valid page entry");
        }

        return array_map(function($pageItem){
                return PageDto::fromArray($pageItem);
            },
            $csvContent
        );
    }
}
