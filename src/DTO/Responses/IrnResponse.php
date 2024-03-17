<?php

namespace Naranarethiya\GstEinvoice\DTO\Responses;

use Naranarethiya\GstEinvoice\Contracts\Arrayable;

class IrnResponse implements Arrayable
{
    public function __construct(
        public string $acknowledgeNumber,
        public string $acknowledgeDate,
        public string $irn,
        public string $signedInvoice,
        public string $signedQRCode,
        public ?string $status,
        public ?string $ewaybillNumber = null,
        public ?string $ewaybillDate = null,
        public ?string $ewaybillValidTill = null,
        public ?string $remark = null,
    ) {
    }

    public function toArray()
    {
        return [
            'acknowledgeNumber' => $this->acknowledgeNumber,
            'acknowledgeDate' => $this->acknowledgeDate,
            'irn' => $this->irn,
            'signedInvoice' => $this->signedInvoice,
            'signedQRCode' => $this->signedQRCode,
            'status' => $this->status,
            'ewaybillNumber' => $this->ewaybillNumber,
            'ewaybillDate' => $this->ewaybillDate,
            'ewaybillValidTill' => $this->ewaybillValidTill,
            'remark' => $this->remark,
        ];
    }
}
