<?php

namespace PSStoreParsing\Exceptions\ApiStore;

use JetBrains\PhpStorm\Pure;

class GetCategoriesException extends \Exception
{
    #[Pure]
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct('Get Categories ' . $message, $code, $previous);
    }
}
