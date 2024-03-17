<?php

namespace Naranarethiya\GstEinvoice\DTO\Responses;

class GstDetailResponse
{
    public function __construct(
        public string $gstin,
        public string $tradeName,
        public string $legalName,
        public string $addressBuilding,
        public ?string $addressBuildingNumber,
        public string $addressBuildingFloor,
        public string $street,
        public string $city,
        public string $stateCode,
        public string $pincode,
        public string $taxpayerType,
        public string $status,
        public string $blockedStatus,
        public string $registrationDate,
        public ?string $deRegistrationDate = null,
    ) {
    }
}
