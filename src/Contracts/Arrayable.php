<?php

namespace Naranarethiya\GstEinvoice\Contracts;

interface Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array<mixed, mixed>
     */
    public function toArray();
}
