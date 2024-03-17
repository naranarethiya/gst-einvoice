<?php

namespace Naranarethiya\GstEinvoice\DTO;

use InvalidArgumentException;
use Naranarethiya\GstEinvoice\Contracts\Arrayable;

class TransactionDetail implements Arrayable
{
    public const SUPPLY_TYPES = [
        'B2B',
        'SEZWP',
        'SEZWOP',
        'EXPWP',
        'EXPWOP',
        'DEXP',
    ];

    protected string $supplyType;

    public function __construct(
        protected bool $appliedIgstOnInSameState = false,
        protected string $taxScheme = 'GST',
        string $supplyType = 'B2B',
        protected bool $reverseChargeApplicable = false,
        protected ?string $ecommerceOperatorGstin = null,
    ) {
        if (! in_array($supplyType, self::SUPPLY_TYPES)) {
            throw new InvalidArgumentException("Invalid $supplyType.");
        }

        $this->supplyType = $supplyType;
    }

    public function toArray()
    {
        return [
            'TaxSch' => $this->taxScheme,
            'SupTyp' => $this->supplyType,
            'RegRev' => $this->reverseChargeApplicable ? 'Y' : 'N',
            'EcmGstin' => $this->ecommerceOperatorGstin,
            'IgstOnIntra' => $this->appliedIgstOnInSameState ? 'Y' : 'N',
        ];
    }
}
