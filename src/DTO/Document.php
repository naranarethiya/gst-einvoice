<?php

namespace Naranarethiya\GstEinvoice\DTO;

use Naranarethiya\GstEinvoice\Contracts\Arrayable;

class Document implements Arrayable
{
    const TYPE_INVOICE = 'INV';

    const TYPE_CREDIT_NOTE = 'CRN';

    const TYPE_DEBIT_NOTE = 'DBN';

    public function __construct(
        protected string $invoiceNumber,
        protected string $date,
        protected string $type = 'INV',
    ) {
    }

    public function toArray()
    {
        return [
            'Typ' => $this->type,
            'No' => $this->invoiceNumber,
            /** @phpstan-ignore-next-line */
            'Dt' => date('d/m/Y', strtotime($this->date)),
        ];
    }
}
