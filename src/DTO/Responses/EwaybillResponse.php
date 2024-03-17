<?php

namespace Naranarethiya\GstEinvoice\DTO\Responses;

class EwaybillResponse
{
    public function __construct(
        public string $ewaybillNumber,
        public string $ewaybillDate,
        public ?string $validTill = null,
        public ?string $status = null,
        public ?string $sellerGstin = null,
    ) {
    }
}
