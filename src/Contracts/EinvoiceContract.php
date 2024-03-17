<?php

namespace Naranarethiya\GstEinvoice\Contracts;

use Naranarethiya\GstEinvoice\DTO\EInvoiceData;
use Naranarethiya\GstEinvoice\DTO\Ewaybill;
use Naranarethiya\GstEinvoice\DTO\IrnCancel;
use Naranarethiya\GstEinvoice\DTO\Responses\EwaybillResponse;
use Naranarethiya\GstEinvoice\DTO\Responses\GstDetailResponse;
use Naranarethiya\GstEinvoice\DTO\Responses\IrnCancelResponse;
use Naranarethiya\GstEinvoice\DTO\Responses\IrnResponse;

interface EinvoiceContract
{
    /**
     * Set credential related data,
     * you can use this to pass any data which remote platform needed like base_url, client_id, username etc
     *
     * @param  array<string, string>  $credential
     */
    public function setCredentialData(array $credential): void;

    /**
     * Enable debug mode for api
     */
    public function enableDebugMode(): void;

    public function getGstin(string $gstin): ?GstDetailResponse;

    public function generateIrn(EInvoiceData $invoiceData): IrnResponse;

    public function getIrn(string $irnNumber): IrnResponse;

    public function getIrnByDocDetail(string $docType, string $docNumber, string $docDate, ?string $supplierGstin = null): IrnResponse;

    public function cancelIrn(IrnCancel $irn): IrnCancelResponse;

    // public function getRejectedIrm(string $date): array;

    public function generateEwaybillByIrn(string $irnNumber, Ewaybill $ewaybill): EwaybillResponse;

    public function getEwaybill(string $irnNumber): EwaybillResponse;
}
