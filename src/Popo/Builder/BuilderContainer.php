<?php

declare(strict_types = 1);

namespace Popo\Builder;

class BuilderContainer
{
    /**
     * @var string
     */
    protected $schemaTemplateString;

    /**
     * @var string
     */
    protected $propertyTemplateString;

    /**
     * @var string
     */
    protected $collectionTemplateString;

    /**
     * @var array
     */
    protected $schemaPluginCollection = [];

    /**
     * @var array
     */
    protected $propertyPluginCollection = [];

    /**
     * @var array
     */
    protected $collectionPluginCollection = [];

    public function getSchemaTemplateString(): string
    {
        return $this->schemaTemplateString;
    }

    public function setSchemaTemplateString(string $schemaTemplateString): BuilderContainer
    {
        $this->schemaTemplateString = $schemaTemplateString;

        return $this;
    }

    public function getPropertyTemplateString(): string
    {
        return $this->propertyTemplateString;
    }

    public function setPropertyTemplateString(string $propertyTemplateString): BuilderContainer
    {
        $this->propertyTemplateString = $propertyTemplateString;

        return $this;
    }

    public function getCollectionTemplateString(): string
    {
        return $this->collectionTemplateString;
    }

    public function setCollectionTemplateString(string $collectionTemplateString): BuilderContainer
    {
        $this->collectionTemplateString = $collectionTemplateString;

        return $this;
    }

    public function getSchemaPluginCollection(): array
    {
        return $this->schemaPluginCollection;
    }

    public function setSchemaPluginCollection(array $schemaPluginCollection): BuilderContainer
    {
        $this->schemaPluginCollection = $schemaPluginCollection;

        return $this;
    }

    public function getPropertyPluginCollection(): array
    {
        return $this->propertyPluginCollection;
    }

    public function setPropertyPluginCollection(array $propertyPluginCollection): BuilderContainer
    {
        $this->propertyPluginCollection = $propertyPluginCollection;

        return $this;
    }

    public function getCollectionPluginCollection(): array
    {
        return $this->collectionPluginCollection;
    }

    public function setCollectionPluginCollection(array $collectionPluginCollection): BuilderContainer
    {
        $this->collectionPluginCollection = $collectionPluginCollection;

        return $this;
    }
}
