<?php

namespace Naranarethiya\GstEinvoice\DTO;

use InvalidArgumentException;
use Naranarethiya\GstEinvoice\Contracts\Arrayable;
use RuntimeException;

class EInvoiceData implements Arrayable
{
    public const API_VERSION = '1.1';

    public Seller $seller;

    public Buyer $buyer;

    public Shipment $shipment;

    /** @var InvoiceItem[] */
    public array $invoiceItems;

    public Ewaybill $ewaybill;

    public InvoiceValue $invoiceValue;

    public Document $document;

    public Payment $payment;

    public TransactionDetail $transactionDetail;

    public function setTransactionData(TransactionDetail $transactionDetail): self
    {
        $this->transactionDetail = $transactionDetail;

        return $this;
    }

    public function setDocument(Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function setSeller(Seller $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    public function setBuyer(Buyer $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function setShipment(Shipment $shipment): self
    {
        $this->shipment = $shipment;

        return $this;
    }

    public function setInvoiceItem(InvoiceItem $items): self
    {
        $this->invoiceItems[] = $items;

        return $this;
    }

    /**
     * @param  InvoiceItem[]  $items
     */
    public function setInvoiceItems(array $items): self
    {
        foreach ($items as $item) {
            if (! $item instanceof InvoiceItem) {
                throw new InvalidArgumentException('Each item in the array must be an instance of InvoiceItem.');
            }
        }

        $this->invoiceItems = array_merge($this->invoiceItems, $items);

        return $this;
    }

    public function setPayment(Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function setEwaybill(Ewaybill $ewaybill): self
    {
        $this->ewaybill = $ewaybill;

        return $this;
    }

    public function setValues(InvoiceValue $InvoiceValue): self
    {
        $this->invoiceValue = $InvoiceValue;

        return $this;
    }

    public function toArray(): array
    {
        $this->validate();

        $return = [
            'Version' => self::API_VERSION,
            'TranDtls' => $this->transactionDetail->toArray(),
            'DocDtls' => $this->document->toArray(),
            'SellerDtls' => $this->seller->toArray(),
            'BuyerDtls' => $this->buyer->toArray(),
        ];

        if (isset($this->shipment)) {
            $return['ShipDtls'] = $this->shipment->toArray();
        }

        if (isset($this->invoiceItems)) {
            $return['ItemList'] = array_map(fn ($item) => $item->toArray(), $this->invoiceItems);
        }

        if (isset($this->invoiceValue)) {
            $return['ValDtls'] = $this->invoiceValue->toArray();
        }

        if (isset($this->payment)) {
            $return['PayDtls'] = $this->payment->toArray();
        }

        if (isset($this->ewaybill)) {
            $return['EwbDtls'] = $this->ewaybill->toArray();
        }

        return $return;
    }

    private function validate(): void
    {
        if (! isset($this->transactionDetail)) {
            throw new RuntimeException('Transaction detail is not set.');
        }

        if (! isset($this->document)) {
            throw new RuntimeException('Document detail is not set.');
        }

        if (! isset($this->seller)) {
            throw new RuntimeException('Seller detail is not set.');
        }

        if (! isset($this->buyer)) {
            throw new RuntimeException('Buyer detail is not set.');
        }

        if (count($this->invoiceItems) < 1) {
            throw new RuntimeException('Invoice items detail is not set.');
        }

        if (! isset($this->invoiceValue)) {
            throw new RuntimeException('invoice values detail is not set.');
        }
    }
}
