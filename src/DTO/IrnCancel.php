<?php

namespace Naranarethiya\GstEinvoice\DTO;

use InvalidArgumentException;
use Naranarethiya\GstEinvoice\Contracts\Arrayable;

class IrnCancel implements Arrayable
{
    public const REASON_DUPLICATE = 1;

    public const REASON_MISTAKE = 2;

    public const REASON_ORDER_CANCEL = 3;

    public const REASON_OTHER = 4;

    /**
     * @var array<int, int>
     */
    private array $validReasons = [1, 2, 3, 4];

    public function __construct(
        protected string $irnNumber,
        protected int $cancelReason,
        protected string $cancelRemark = '',
    ) {
        if (! in_array($cancelReason, $this->validReasons)) {
            throw new InvalidArgumentException('Invalid Cancel reason.');
        }
    }

    public function toArray()
    {
        return [
            'Irn' => $this->irnNumber,
            'CnlRsn' => (string) $this->cancelReason,
            'CnlRem' => $this->cancelRemark,
        ];
    }
}
