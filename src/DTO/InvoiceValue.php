<?php

namespace Naranarethiya\GstEinvoice\DTO;

use Naranarethiya\GstEinvoice\Contracts\Arrayable;

class InvoiceValue implements Arrayable
{
    public function __construct(
        protected float $preTaxvalue,
        protected float $totalInvoiceValue,
        protected float $cgstValue,
        protected float $sgstValue,
        protected float $igstValue,
        // do not pass this discount if you are passing discount amount at item level
        protected float $discountAmountAfterItemDiscounts = 0,
        protected float $otherCharges = 0,
        protected float $roundoffAmount = 0,
        protected ?float $finalAmountOtherCurrency = null
    ) {
    }

    public function toArray()
    {
        $return = [
            'AssVal' => (string) $this->preTaxvalue,
            'CgstVal' => (string) $this->cgstValue,
            'SgstVal' => (string) $this->sgstValue,
            'IgstVal' => (string) $this->igstValue,
            'Discount' => (string) $this->discountAmountAfterItemDiscounts,
            'OthChrg' => (string) $this->otherCharges,
            'RndOffAmt' => (string) $this->roundoffAmount,
            'TotInvVal' => (string) $this->totalInvoiceValue,
            'TotInvValFc' => (string) $this->finalAmountOtherCurrency,
        ];

        return array_filter($return);
    }
}
