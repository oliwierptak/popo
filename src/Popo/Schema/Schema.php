<?php

declare(strict_types = 1);

namespace Popo\Schema;

use Popo\Schema\Config\Config;
use Popo\Schema\Property\Property;

class Schema
{
    protected string $name;
    protected string $schemaName;
    protected Config $config;
    /**
     * @var array<string, \Popo\Schema\Property\Property>
     */
    protected array $propertyCollection = [];
    /**
     * @var array<string, mixed>
     */
    protected array $default = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSchemaName(): string
    {
        return $this->schemaName;
    }

    public function setSchemaName(string $schemaName): self
    {
        $this->schemaName = $schemaName;

        return $this;
    }

    /**
     * @return array<string, \Popo\Schema\Property\Property>
     */
    public function getPropertyCollection(): array
    {
        return $this->propertyCollection;
    }

    /**
     * @param array<string, \Popo\Schema\Property\Property> $propertyCollection
     *
     * @return $this
     */
    public function setPropertyCollection(array $propertyCollection): self
    {
        $this->propertyCollection = $propertyCollection;

        return $this;
    }

    public function addPropertyCollectionItem(Property $property): self
    {
        $this->propertyCollection[$property->getItemName()] = $property;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getDefault(): array
    {
        return $this->default;
    }

    /**
     * @param array<string, mixed> $default
     *
     * @return $this
     */
    public function setDefault(array $default): self
    {
        $this->default = $default;

        return $this;
    }

    public function getConfig(): Config
    {
        if (empty($this->config)) {
            $this->config = new Config();
        }

        return $this->config;
    }

    public function setConfig(Config $config): self
    {
        $this->config = $config;

        return $this;
    }
}
