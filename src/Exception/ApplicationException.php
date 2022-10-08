<?php

namespace App\Exception;

class ApplicationException extends \Exception
{
    public function __construct(
        string $message
    ){
        parent::__construct($message);
    }
}
