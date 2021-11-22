<?php

declare(strict_types = 1);

namespace Popo\Schema;

class Schema
{
    protected const PROPERTY_SHAPE = [Property::class];

    protected string $name;
    protected string $schemaName;
    protected Config $config;
    /**
     * @var \Popo\Schema\Property[]
     */
    
    protected array $propertyCollection = [];
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

    
    public function getPropertyCollection(): array
    {
        return $this->propertyCollection;
    }

    /**
     * @param \Popo\Schema\Property[] $propertyCollection
     *
     * @return $this
     */
    public function setPropertyCollection( array $propertyCollection): self
    {
        $this->propertyCollection = $propertyCollection;

        return $this;
    }

    public function getDefault(): array
    {
        return $this->default;
    }

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
