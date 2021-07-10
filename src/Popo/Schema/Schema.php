<?php

declare(strict_types = 1);

namespace Popo\Schema;

use JetBrains\PhpStorm\ArrayShape;
use Popo\PopoConfigurator;

class Schema
{
    protected const SCHEMA_SHAPE = [
        'schemaName' => 'string',
        'name' => 'string',
        'namespace' => 'string',
        'propertyCollection' => [Property::class],
        'configurator' => PopoConfigurator::class,
    ];

    protected const PROPERTY_SHAPE = [Property::class];

    protected ?PopoConfigurator $configurator = null;
    protected string $schemaName;
    protected string $name;
    protected string $namespace;
    /**
     * @var \Popo\Schema\Property[]
     */
    #[ArrayShape([Property::class])]
    protected array $propertyCollection = [];

    public function getSchemaName(): string
    {
        return $this->schemaName;
    }

    public function setSchemaName(string $schemaName): self
    {
        $this->schemaName = $schemaName;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    #[ArrayShape(self::PROPERTY_SHAPE)]
    public function getPropertyCollection(): array
    {
        return $this->propertyCollection;
    }

    public function setPropertyCollection(
        #[ArrayShape(self::PROPERTY_SHAPE)]
        array $propertyCollection
    ): self {
        $this->propertyCollection = $propertyCollection;

        return $this;
    }

    public function getConfigurator(): PopoConfigurator
    {
        return $this->configurator;
    }

    public function setConfigurator(PopoConfigurator $configurator): self
    {
        $this->configurator = $configurator;

        return $this;
    }

    #[ArrayShape(self::SCHEMA_SHAPE)]
    public function toArray(): array
    {
        return [
            'schemaName' => $this->schemaName,
            'name' => $this->name,
            'namespace' => $this->namespace,
            'propertyCollection' => $this->propertyCollection,
            'configurator' => $this->configurator,
        ];
    }

    public function fromArray(
        #[ArrayShape(self::SCHEMA_SHAPE)]
        array $data
    ): self {
        $this->schemaName = $data['schemaName'];
        $this->name = $data['name'];
        $this->namespace = $data['namespace'];
        $this->propertyCollection = $data['propertyCollection'];
        $this->configurator = $data['configurator'];

        return $this;
    }
}
