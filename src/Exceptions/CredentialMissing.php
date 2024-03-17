<?php

namespace Naranarethiya\GstEinvoice\Exceptions;

use Exception;

class CredentialMissing extends Exception
{
    public function __construct(
        string $message,
    ) {
        parent::__construct($message);
    }
}
