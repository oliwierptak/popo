<?php

declare(strict_types = 1);

namespace Popo\Schema\Property;

use Popo\PopoDefinesInterface;

class PropertyExtraTimezone
{
    protected array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function hasTimezone(): bool
    {
        return !empty($this->data[PopoDefinesInterface::PROPERTY_TYPE_EXTRA_TIMEZONE]);
    }

    public function getTimezone(): string
    {
        return $this->data[PopoDefinesInterface::PROPERTY_TYPE_EXTRA_TIMEZONE];
    }

    public function getFormat(): string
    {
        return $this->data[PopoDefinesInterface::PROPERTY_TYPE_EXTRA_FORMAT] ?? \DATE_ATOM;
    }
}
