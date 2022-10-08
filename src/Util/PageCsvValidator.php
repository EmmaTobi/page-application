<?php

namespace App\Util;

use App\Exception\ApplicationException;

class PageCsvValidator
{
    public const URL_VALIDATOR = "url_validator";
    public const URL_VALIDATOR_KEY = "url";

    public const VALIDATORS = [
         self::URL_VALIDATOR => self::URL_VALIDATOR_KEY
    ];

    private array $validators = [];

    /**
     * @throws ApplicationException
     */
    public function addValidator(string $validator): void
    {
        if (isset(self::VALIDATORS[$validator])){
            $this->validators[] = $validator;

            return;
        }

        throw new ApplicationException(sprintf("validator [%s] does not exist", $validator));
    }

    public function validate(array $pageItem): bool
    {
        $passes  = false;

        foreach($this->validators as $validator){
            switch ($validator) {
                case self::URL_VALIDATOR:
                    $passes = $this->validateUrl($pageItem);
                    break;
                default;
            }
        }

        return $passes;
    }

    protected function validateUrl(array $pageItem): bool
    {
        $key = self::VALIDATORS[self::URL_VALIDATOR];

        if(isset($pageItem[$key])){
            $url  = $pageItem[self::URL_VALIDATOR_KEY];

            return filter_var($url, FILTER_VALIDATE_URL);
        }

        return false;
    }
}
