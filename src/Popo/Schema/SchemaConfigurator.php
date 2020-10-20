<?php

declare(strict_types = 1);

namespace Popo\Schema;

class SchemaConfigurator
{
    /**
     * @var string
     */
    protected $schemaPath = '@schema@';

    /**
     * @var string
     */
    protected $schemaFilename = '*.schema.json';

    /**
     * @var string
     */
    protected $schemaTemplateFilename = 'php.schema.tpl';

    /**
     * @var string
     */
    protected $arrayableTemplateFilename = 'php.arrayable.tpl';

    /**
     * @var string
     */
    protected $propertyTemplateFilename = 'php.property.tpl';

    /**
     * @var string
     */
    protected $collectionTemplateFilename = 'php.collection.tpl';

    public function getSchemaPath(): string
    {
        return $this->schemaPath;
    }

    public function setSchemaPath(string $schemaPath): self
    {
        $this->schemaPath = $schemaPath;

        return $this;
    }

    public function getSchemaFilename(): string
    {
        return $this->schemaFilename;
    }

    public function setSchemaFilename(string $schemaFilename): self
    {
        $this->schemaFilename = $schemaFilename;

        return $this;
    }

    public function resolveBundleName(string $schemaFilename, string $delimiter = '.'): string
    {
        $parts = \explode($delimiter, $schemaFilename);

        return \current($parts);
    }

    public function getSchemaTemplateFilename(): string
    {
        return $this->schemaTemplateFilename;
    }

    public function getArrayableTemplateFilename(): string
    {
        return $this->arrayableTemplateFilename;
    }

    public function setSchemaTemplateFilename(string $schemaTemplateFilename): self
    {
        $this->schemaTemplateFilename = $schemaTemplateFilename;

        return $this;
    }

    public function getPropertyTemplateFilename(): string
    {
        return $this->propertyTemplateFilename;
    }

    public function setPropertyTemplateFilename(string $propertyTemplateFilename): self
    {
        $this->propertyTemplateFilename = $propertyTemplateFilename;

        return $this;
    }

    public function getCollectionTemplateFilename(): string
    {
        return $this->collectionTemplateFilename;
    }

    public function setCollectionTemplateFilename(string $collectionTemplateFilename): self
    {
        $this->collectionTemplateFilename = $collectionTemplateFilename;

        return $this;
    }
}
