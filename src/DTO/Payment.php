<?php

namespace Naranarethiya\GstEinvoice\DTO;

use Naranarethiya\GstEinvoice\Contracts\Arrayable;

class Payment implements Arrayable
{
    public function __construct(
        protected string $companyName,
        protected string $payeeAccountNumber = '',
        protected string $paymentMode = '',
        protected string $branchOrIFSC = '',
        protected string $terms = '',
        protected string $instruction = '',
        protected string $creditTransfer = '',
        protected string $directDebit = '',
        protected string $creditDays = '',
        protected string $paidAmount = '',
        protected string $paymentDue = '',
    ) {
    }

    public function toArray()
    {
        return [
            'Nm' => $this->companyName,
            'Accdet' => $this->payeeAccountNumber,
            'Mode' => $this->paymentMode,
            'Fininsbr' => $this->branchOrIFSC,
            'Payterm' => $this->terms,
            'Payinstr' => $this->instruction,
            'Crtrn' => $this->creditTransfer,
            'Dirdr' => $this->directDebit,
            'Crday' => $this->creditDays,
            'Paidamt' => $this->paidAmount,
            'Paymtdue' => $this->paymentDue,
        ];
    }
}
