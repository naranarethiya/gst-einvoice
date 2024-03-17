<?php

namespace Naranarethiya\GstEinvoice\DTO;

use Naranarethiya\GstEinvoice\Contracts\Arrayable;

class Buyer implements Arrayable
{
    public function __construct(
        protected string $gstin,
        protected string $companyName,
        protected string $address1,
        protected string $city,
        protected string $pin,
        protected int $stateCode,
        protected int $stateCodeOfPlaceOfSupply,
        protected string $tradeName = '',
        protected string $phone = '',
        protected string $email = '',
        protected string $address2 = '',
    ) {

    }

    public function toArray(): array
    {
        $return = [
            'Gstin' => $this->gstin,
            'LglNm' => $this->companyName,
            'TrdNm' => $this->tradeName,
            'Pos' => (string) $this->stateCodeOfPlaceOfSupply,
            'Addr1' => $this->address1,
            'Addr2' => $this->address2,
            'Loc' => $this->city,
            'Pin' => (string) $this->pin,
            'Stcd' => (string) $this->stateCode,
            'Ph' => $this->phone,
            'Em' => $this->email,
        ];

        return array_filter($return);
    }
}
