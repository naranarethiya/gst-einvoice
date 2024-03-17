<?php

namespace Naranarethiya\GstEinvoice\DTO;

use InvalidArgumentException;
use Naranarethiya\GstEinvoice\Contracts\Arrayable;

class Ewaybill implements Arrayable
{
    public const TRANSPORT_MODES_ROAD = 'Road';

    public const TRANSPORT_MODES_RAIL = 'Rail';

    public const TRANSPORT_MODES_AIR = 'Air';

    public const TRANSPORT_MODES_SHIP = 'Ship';

    public const VEHICLE_REGULAR = 'reguler';

    public const VEHICLE_ODC = 'ODC';

    public const TRANSPORT_MODES = [
        self::TRANSPORT_MODES_ROAD => '1',
        self::TRANSPORT_MODES_RAIL => '2',
        self::TRANSPORT_MODES_AIR => '3',
        self::TRANSPORT_MODES_SHIP => '4',
    ];

    public const TRANSPORT_MODES_MAP = [
        '1' => self::TRANSPORT_MODES_ROAD,
        '2' => self::TRANSPORT_MODES_RAIL,
        '3' => self::TRANSPORT_MODES_AIR,
        '4' => self::TRANSPORT_MODES_SHIP,
    ];

    public const vehicleTypes = [
        self::VEHICLE_REGULAR => 'R',
        self::VEHICLE_ODC => 'O',
    ];

    public function __construct(
        public int|string $distance = '0',
        public ?string $trasportMode = null,
        public ?string $transporterGstIn = null,
        public ?string $transporterName = null,
        public string $vehicleType = self::vehicleTypes[self::VEHICLE_REGULAR],
        public ?string $vehicleNumber = null,
        public ?string $transportDocNumber = null,
        public ?string $transportDocDate = null,
    ) {
        if (! empty($this->trasportMode)) {
            if (! in_array($this->trasportMode, self::TRANSPORT_MODES)) {
                throw new InvalidArgumentException('Invalid transportation Mode.');
            }
        }
        if (! empty($this->vehicleType)) {
            if (! in_array(strtoupper($this->vehicleType), self::vehicleTypes)) {
                throw new InvalidArgumentException('Invalid vehicle Type.');
            }
        }

        if ($this->trasportMode == self::TRANSPORT_MODES[self::TRANSPORT_MODES_ROAD]) {
            if (empty($this->vehicleType) || empty($this->vehicleNumber)) {
                throw new InvalidArgumentException('For trasportMode Road, vehicle type and vehicle number are required.');
            }
        }

        if (in_array($this->trasportMode, [2, 3, 4])) {
            if (empty($this->transportDocNumber) || empty($this->transportDocDate)) {
                throw new InvalidArgumentException('For trasportMode Air, Rail and Ship, Transport document and transport document date are required.');
            }
        }
    }

    public function toArray()
    {
        $return = [
            'Transid' => $this->transporterGstIn,
            'Transname' => $this->transporterName,
            'Distance' => (string) $this->distance,
            'Transdocno' => $this->transportDocNumber,
            /** @phpstan-ignore-next-line */
            'TransdocDt' => $this->transportDocDate ? date('d/m/Y', strtotime($this->transportDocDate)) : null,
            'Vehno' => $this->vehicleNumber,
            'Vehtype' => $this->vehicleType,
            'TransMode' => $this->trasportMode,
        ];

        return array_filter($return);
    }
}
