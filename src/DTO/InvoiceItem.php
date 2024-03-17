<?php

namespace Naranarethiya\GstEinvoice\DTO;

use Naranarethiya\GstEinvoice\Contracts\Arrayable;

class InvoiceItem implements Arrayable
{
    public function __construct(
        protected int $serialNumber,
        protected bool $isService,
        protected int|string $hsnCode,
        protected float $qty,
        protected float $unitPrice,
        protected float $totalAmount,
        // ((price * rate) - Discount)
        protected float $itemValue,
        protected float $gstRate,
        // ((price * rate) - Discount + tax)
        protected float $finalItemValue,
        protected string $unit,
        protected float $sgstAmount,
        protected float $cgstAmount,
        protected float $igstAmount,
        protected ?float $preTaxValue = null,
        protected ?string $productDescription = null,
        protected ?float $freeQty = null,
        protected ?float $discountAmount = null,
        protected ?float $otherCharges = null,
        protected ?string $orginCountry = null,
        protected ?string $productSerialNumber = null,
    ) {
    }

    public function toArray()
    {
        $return = [
            'SlNo' => (string) $this->serialNumber,
            'IsServc' => $this->isService ? 'Y' : 'N',
            'PrdDesc' => $this->productDescription,
            'HsnCd' => (string) $this->hsnCode,
            'Qty' => (string) $this->qty,
            'FreeQty' => $this->freeQty,
            'Unit' => $this->unit,
            'UnitPrice' => (string) $this->unitPrice,
            'TotAmt' => (string) $this->totalAmount,
            'Discount' => (string) $this->discountAmount,
            'PreTaxVal' => (string) $this->preTaxValue,
            'AssAmt' => (string) $this->itemValue,
            'GstRt' => (string) $this->gstRate,
            'SgstAmt' => (string) $this->sgstAmount,
            'IgstAmt' => (string) $this->igstAmount,
            'CgstAmt' => (string) $this->cgstAmount,
            'OthChrg' => (string) $this->otherCharges,
            'TotItemVal' => (string) $this->finalItemValue,
            'TotInvValFc' => (string) $this->finalItemValue,
            'PrdSlNo' => $this->productSerialNumber,
        ];

        return array_filter($return);
    }
}
