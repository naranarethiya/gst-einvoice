<?php

namespace Naranarethiya\GstEinvoice\DTO;

use Naranarethiya\GstEinvoice\Contracts\Arrayable;

class Shipment implements Arrayable
{
    public function __construct(
        protected string $gstin,
        protected string $companyName,
        protected string $address1,
        protected string $city,
        protected int $pin,
        protected int $stateCode,
        protected string $tradeName = '',
        protected string $address2 = '',
    ) {

    }

    public function toArray()
    {
        return [
            'Gstin' => $this->gstin,
            'LglNm' => $this->companyName,
            'TrdNm' => $this->tradeName,
            'Addr1' => $this->address1,
            'Addr2' => $this->address2,
            'Loc' => $this->city,
            'Pin' => $this->pin,
            'Stcd' => $this->stateCode,
        ];
    }
}
