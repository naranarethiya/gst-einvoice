<?php

namespace Naranarethiya\GstEinvoice\Exceptions;

use Exception;

class RequestFailed extends Exception
{
    /**
     * @param array<string, mixed> $response
     */
    public function __construct(
        string $message,
        int $code = 500,
        public array $response = [],
    ) {

        parent::__construct($message, $code);
    }
}
