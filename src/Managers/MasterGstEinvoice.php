<?php

namespace Naranarethiya\GstEinvoice\Managers;

use Exception;
use GuzzleHttp\Client;
use InvalidArgumentException;
use Naranarethiya\GstEinvoice\Contracts\EinvoiceContract;
use Naranarethiya\GstEinvoice\DTO\Document;
use Naranarethiya\GstEinvoice\DTO\EInvoiceData;
use Naranarethiya\GstEinvoice\DTO\Ewaybill;
use Naranarethiya\GstEinvoice\DTO\IrnCancel;
use Naranarethiya\GstEinvoice\DTO\Responses\EwaybillResponse;
use Naranarethiya\GstEinvoice\DTO\Responses\GstDetailResponse;
use Naranarethiya\GstEinvoice\DTO\Responses\IrnCancelResponse;
use Naranarethiya\GstEinvoice\DTO\Responses\IrnResponse;
use Naranarethiya\GstEinvoice\Exceptions\CredentialMissing;
use Naranarethiya\GstEinvoice\Exceptions\RequestFailed;
use Psr\Http\Message\ResponseInterface;

class MasterGstEinvoice implements EinvoiceContract
{
    const DEFAULT_BASE_URL = 'https://api.mastergst.com/einvoice/';

    const DEFAULT_VERSION_STR = 'version/V1_03';

    private ?string $baseUrl;

    private ?string $versionString;

    private ?string $companyGstin;

    private ?string $email;

    private ?string $username;

    private ?string $password;

    private ?string $clientId;

    private ?string $clientSecret;

    private ?string $ipAddress;

    private bool $debug = false;

    private ?string $authToken;

    public function setCredentialData(array $credential): void
    {
        $this->email = $credential['email'] ?? null;
        $this->username = $credential['username'] ?? null;
        $this->password = $credential['password'] ?? null;
        $this->clientId = $credential['client_id'] ?? null;
        $this->clientSecret = $credential['client_secret'] ?? null;
        $this->ipAddress = $credential['ip_address'] ?? null;
        $this->companyGstin = $credential['company_gstin'] ?? null;
        $this->baseUrl = $credential['base_url'] ?? self::DEFAULT_BASE_URL;
        $this->versionString = $credential['version_str'] ?? self::DEFAULT_VERSION_STR;
    }

    public function generateIrn(EInvoiceData $invoiceData): IrnResponse
    {
        $uri = $this->getUri('type/GENERATE');

        $data = $invoiceData->toArray();
        $response = $this->sendRequest(
            $uri,
            $data,
            $this->getDefaultHeaders(),
            'post'
        );

        return new IrnResponse(
            acknowledgeNumber: $response['data']['AckNo'],
            acknowledgeDate: $response['data']['AckDt'],
            irn: $response['data']['Irn'],
            signedInvoice: $response['data']['SignedInvoice'],
            signedQRCode: $response['data']['SignedQRCode'],
            status: $response['data']['Status'],
            ewaybillNumber: $response['data']['EwbNo'],
            ewaybillDate: $response['data']['EwbDt'],
            ewaybillValidTill: $response['data']['EwbValidTill'],
            remark: $response['data']['Remarks'],
        );
    }

    public function generateEwaybillByIrn(string $irnNumber, Ewaybill $ewaybill): EwaybillResponse
    {
        $url = $this->getUri('type/GENERATE_EWAYBILL');
        $headers = $this->getDefaultHeaders();
        $requestData = $ewaybill->toArray();
        $requestData['Irn'] = $irnNumber;

        if (empty($requestData['Distance'])) {
            $requestData['Distance'] = '0';
        }

        $response = $this->sendRequest(
            $url,
            $requestData,
            $headers,
            'post'
        );

        return new EwaybillResponse(
            ewaybillNumber: $response['data']['EwbNo'],
            ewaybillDate: $response['data']['EwbDt'],
            validTill: $response['data']['EwbValidTill'],
        );
    }

    public function cancelIrn(IrnCancel $irnCancel): IrnCancelResponse
    {
        $url = $this->getUri('type/CANCEL');
        $headers = $this->getDefaultHeaders();
        $response = $this->sendRequest(
            $url,
            $irnCancel->toArray(),
            $headers,
            'post'
        );

        return new IrnCancelResponse(
            irn: $response['data']['Irn'],
            cancelDate: $response['data']['CancelDate'],
        );
    }

    public function getGstin(string $gstin): ?GstDetailResponse
    {
        $url = $this->getUri('type/GSTNDETAILS');
        $headers = $this->getDefaultHeaders();
        $data = [
            'param1' => $gstin,
            'email' => $this->email,
        ];
        $response = $this->sendRequest($url, $data, $headers);

        $response['data'] = array_map('trim', $response['data']);

        return new GstDetailResponse(
            gstin: $response['data']['Gstin'],
            tradeName: $response['data']['TradeName'],
            legalName: $response['data']['LegalName'],
            addressBuilding: $response['data']['AddrBnm'],
            addressBuildingNumber: $response['data']['AddrBno'],
            addressBuildingFloor: $response['data']['AddrFlno'],
            street: $response['data']['AddrSt'],
            city: $response['data']['AddrLoc'],
            stateCode: $response['data']['StateCode'],
            pincode: $response['data']['AddrPncd'],
            taxpayerType: $response['data']['TxpType'],
            status: $response['data']['Status'],
            blockedStatus: $response['data']['BlkStatus'],
            registrationDate: $response['data']['DtReg'],
            deRegistrationDate: $response['data']['DtDReg'],
        );
    }

    public function getIrn(string $irnNumber): IrnResponse
    {
        $url = $this->getUri('type/GETIRN', false);
        $headers = $this->getDefaultHeaders();
        $requestData['param1'] = $irnNumber;
        $requestData['email'] = urlencode($this->email ?? '');

        $response = $this->sendRequest(
            $url,
            $requestData,
            $headers,
            'get'
        );

        return new IrnResponse(
            acknowledgeNumber: $response['data']['AckNo'],
            acknowledgeDate: $response['data']['AckDt'],
            irn: $response['data']['Irn'],
            signedInvoice: $response['data']['SignedInvoice'],
            signedQRCode: $response['data']['SignedQRCode'],
            status: $response['data']['Status'],
            ewaybillNumber: $response['data']['EwbNo'],
            ewaybillDate: $response['data']['EwbDt'],
            ewaybillValidTill: $response['data']['EwbValidTill'],
            remark: $response['data']['Remarks'],
        );
    }

    public function getIrnByDocDetail(string $docType, string $docNumber, string $docDate, ?string $supplierGstin = null): IrnResponse
    {
        $validDocumentTypes = [
            Document::TYPE_INVOICE,
            Document::TYPE_DEBIT_NOTE,
            Document::TYPE_CREDIT_NOTE,
        ];
        if (!in_array($docType, $validDocumentTypes)) {
            throw new InvalidArgumentException('Invalid docType value, it can only be ' . implode(', ', $validDocumentTypes));
        }

        $time = strtotime($docDate);
        if (!$time) {
            throw new InvalidArgumentException('Invalid docDate value');
        }

        $url = $this->getUri('type/GETIRNBYDOCDETAILS', false);
        $headers = $this->getDefaultHeaders();
        $requestData['param1'] = $docType;
        $requestData['email'] = urlencode($this->email ?? '');

        $headers['docnum'] = $docNumber;
        $headers['docdate'] = date('d/m/Y', $time);

        $response = $this->sendRequest(
            $url,
            $requestData,
            $headers,
            'get'
        );

        return new IrnResponse(
            acknowledgeNumber: $response['data']['AckNo'],
            acknowledgeDate: $response['data']['AckDt'],
            irn: $response['data']['Irn'],
            signedInvoice: $response['data']['SignedInvoice'],
            signedQRCode: $response['data']['SignedQRCode'],
            status: $response['data']['Status'],
            ewaybillNumber: $response['data']['EwbNo'],
            ewaybillDate: $response['data']['EwbDt'],
            ewaybillValidTill: $response['data']['EwbValidTill'],
            remark: $response['data']['Remarks'],
        );
    }

    // public function getRejectedIrm(string $date): array
    // {
    //     return [];
    // }

    public function getEwaybill(string $irnNumber): EwaybillResponse
    {
        $url = $this->getUri('type/GETEWAYBILLIRN', false);
        $headers = $this->getDefaultHeaders();
        $requestData['param1'] = $irnNumber;
        $requestData['email'] = urlencode($this->email ?? '');

        $response = $this->sendRequest(
            $url,
            $requestData,
            $headers,
            'get'
        );

        return new EwaybillResponse(
            ewaybillNumber: $response['data']['EwbNo'],
            ewaybillDate: $response['data']['EwbDt'],
            validTill: $response['data']['EwbValidTill'],
            status: $response['data']['Status'],
            sellerGstin: $response['data']['GenGstin'],
        );
    }

    public function enableDebugMode(): void
    {
        $this->debug = true;
    }

    private function setAuthToken(): void
    {
        if (empty($this->authToken)) {
            $authToken = $this->getAuthToken();
            $this->authToken = $authToken['AuthToken'];
        }
    }

    /**
     * @return array<string, string>
     */
    private function getAuthToken(): array
    {
        $headers = $this->getDefaultHeaders(setAuthToken: false);

        $responseData = $this->sendRequest('authenticate', ['email' => $this->email], $headers);

        return $responseData['data'];
    }

    /**
     * @return array<string, string|null>
     */
    private function getDefaultHeaders(bool $setAuthToken = true): array
    {
        $this->validateCredsDetail();

        $headers = [
            'accept' => '*/*',
            'username' => $this->username,
            'password' => $this->password,
            'ip_address' => $this->ipAddress,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'gstin' => $this->companyGstin,
        ];

        if ($setAuthToken) {
            $this->setAuthToken();
            $headers['auth-token'] = $this->authToken;
        }

        return $headers;
    }

    private function getUri(string $uri, bool $appendEmail = true): string
    {
        $uri = trim($uri, '/');
        $url = $uri . '/' . $this->versionString;

        if ($appendEmail) {
            $url .= '?email=' . urlencode($this->email ?? '');
        }

        return $url;
    }

    /**
     * @return array<string, mixed>
     */
    private function getDebugOptions(): array
    {
        $debugOptions = [];
        if ($this->debug) {
            $debugOptions = [
                'verify' => false,
                'timeout' => 30,
            ];
        }

        return $debugOptions;
    }

    /**
     * @param array{data: array<string, string>, status_cd: string, status_desc: string|array<string, string>} $response
     */
    private function throwErrorIfResponseFailed(array $response): void
    {
        /** @phpstan-ignore-next-line */
        if (!isset($response['status_cd']) || in_array(!strtolower($response['status_cd']), ['sucess', '1'])) {
            /** @phpstan-ignore-next-line */
            if (isset($response['status_desc'])) {
                /** @phpstan-ignore-next-line */
                $error = json_decode($response['status_desc'], true);
                if (!empty($error)) {
                    /** @phpstan-ignore-next-line */
                    throw new RequestFailed((string) $error[0]['ErrorMessage'], (int) $error[0]['ErrorCode'], $response);
                } else {
                    /** @phpstan-ignore-next-line */
                    throw new RequestFailed((string) $response['status_desc'], (int) $response['status_cd'], $response);
                }
            }
            /** @phpstan-ignore-next-line */
            throw new \RuntimeException('Unknown error occured');
        }
    }

    // private function logApi(string $url, array $headers, array $data)
    // {
    //     // TODO: Log the record
    // }

    /**
     * sendRequest
     *
     * @param  array<string|int, mixed>  $data
     * @param  array<string|int, mixed>  $headers
     * @return array{data: array<string, string>, status_cd: string, status_desc: string|array<string, string>}
     */
    private function sendRequest(string $uri, array $data, array $headers, string $method = 'get'): array
    {
        $client = new Client([
            'base_uri' => $this->baseUrl,
        ]);

        try {
            $requestData = [
                'headers' => $headers,
                'json' => $data,
                ...$this->getDebugOptions(),
            ];

            if (strtolower($method) == 'get') {
                unset($requestData['json']);
                $requestData['query'] = $data;
            }

            /** @var ResponseInterface $response */
            $response = $client->request($method, $uri, $requestData);

            /** @var array{data: array<string, string>, status_cd: string, status_desc: string|array<string, string>} $responseBody */
            $responseBody = json_decode(
                $response->getBody()->getContents(),
                true
            );
            /** @phpstant-ignore-next-line */
            $this->throwErrorIfResponseFailed($responseBody);

            /** @phpstant-ignore-next-line */
            return $responseBody;
        } catch (Exception $e) {
            // TODO: log the record
            throw $e;
        }
    }

    private function validateCredsDetail(): void
    {
        if (empty($this->email)) {
            throw new CredentialMissing('email is missing, please set email using function setCredentialData.');
        }

        if (empty($this->username)) {
            throw new CredentialMissing('username is missing, please set username using function setCredentialData.');
        }

        if (empty($this->password)) {
            throw new CredentialMissing('password is missing, please set password using function setCredentialData.');
        }

        if (empty($this->clientId)) {
            throw new CredentialMissing('client_id is missing, please set client_id using function setCredentialData.');
        }

        if (empty($this->clientSecret)) {
            throw new CredentialMissing('client_secret is missing, please set client_secret using function setCredentialData.');
        }

        if (empty($this->ipAddress)) {
            throw new CredentialMissing('ip_address is missing, please set ip_address using function setCredentialData.');
        }

        if (empty($this->companyGstin)) {
            throw new CredentialMissing('company_gstin is missing, please set company_gstin using function setCredentialData.');
        }
    }
}
