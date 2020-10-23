<?php declare(strict_types = 1);

namespace Popo\Builder;

class BuilderContainer
{
    protected string $schemaTemplateString;
    protected string $propertyTemplateString;
    protected string $arrayableTemplateString;
    protected string $collectionTemplateString;
    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected array $schemaPluginCollection = [];
    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected array $propertyPluginCollection = [];
    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected array $arrayablePluginCollection = [];
    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected array $collectionPluginCollection = [];

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

    /**
     * @return \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    public function getSchemaPluginCollection(): array
    {
        return $this->schemaPluginCollection;
    }

    /**
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[] $schemaPluginCollection
     *
     * @return $this
     */
    public function setSchemaPluginCollection(array $schemaPluginCollection): BuilderContainer
    {
        $this->schemaPluginCollection = $schemaPluginCollection;

        return $this;
    }

    /**
     * @return \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    public function getPropertyPluginCollection(): array
    {
        return $this->propertyPluginCollection;
    }

    /**
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[] $propertyPluginCollection
     *
     * @return $this
     */
    public function setPropertyPluginCollection(array $propertyPluginCollection): BuilderContainer
    {
        $this->propertyPluginCollection = $propertyPluginCollection;

        return $this;
    }

    /**
     * @return \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    public function getCollectionPluginCollection(): array
    {
        return $this->collectionPluginCollection;
    }

    /**
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[] $collectionPluginCollection
     *
     * @return $this
     */
    public function setCollectionPluginCollection(array $collectionPluginCollection): BuilderContainer
    {
        $this->collectionPluginCollection = $collectionPluginCollection;

        return $this;
    }

    public function getArrayableTemplateString(): string
    {
        return $this->arrayableTemplateString;
    }

    public function setArrayableTemplateString(string $arrayableTemplateString): self
    {
        $this->arrayableTemplateString = $arrayableTemplateString;

        return $this;
    }

    /**
     * @return \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    public function getArrayablePluginCollection(): array
    {
        return $this->arrayablePluginCollection;
    }

    /**
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[] $arrayablePluginCollection
     *
     * @return $this
     */
    public function setArrayablePluginCollection(array $arrayablePluginCollection): self
    {
        $this->arrayablePluginCollection = $arrayablePluginCollection;

        return $this;
    }
}
