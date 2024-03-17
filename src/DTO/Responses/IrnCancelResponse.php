<?php

namespace Naranarethiya\GstEinvoice\DTO\Responses;

class IrnCancelResponse
{
    public function __construct(
        public string $irn = '',
        public string $cancelDate = '',
        public string $status = '',
        public string $data = '',
        public string $ErrorDetails = '',
        public string $InfoDtls = '',
    ) {
    }
}
