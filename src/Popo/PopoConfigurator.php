<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Plugin\ClassPlugin\DateTimeMethodClassPlugin;
use Popo\Plugin\ClassPlugin\ExtendClassPlugin;
use Popo\Plugin\ClassPlugin\FromArrayClassPlugin;
use Popo\Plugin\ClassPlugin\ImplementClassPlugin;
use Popo\Plugin\ClassPlugin\IsNewClassPlugin;
use Popo\Plugin\ClassPlugin\ListModifiedPropertiesClassPlugin;
use Popo\Plugin\ClassPlugin\MetadataClassPlugin;
use Popo\Plugin\ClassPlugin\PopoMethodClassPlugin;
use Popo\Plugin\ClassPlugin\RequireAllClassPlugin;
use Popo\Plugin\ClassPlugin\ToArrayClassPlugin;
use Popo\Plugin\ClassPlugin\UpdateMapClassPlugin;
use Popo\Plugin\PropertyPlugin\AddItemPropertyMethodPlugin;
use Popo\Plugin\PropertyPlugin\DefinePropertyPlugin;
use Popo\Plugin\PropertyPlugin\GetPropertyMethodPlugin;
use Popo\Plugin\PropertyPlugin\HasPropertyMethodPlugin;
use Popo\Plugin\PropertyPlugin\RequirePropertyMethodPlugin;
use Popo\Plugin\PropertyPlugin\SetPropertyMethodPlugin;

class PopoConfigurator
{
    protected ?string $namespace = null;
    protected ?string $namespaceRoot = null;
    protected ?string $outputPath = null;
    protected string $schemaPath;
    protected ?string $schemaPathFilter = null;
    protected ?string $schemaFilenameMask = '*.popo.yml';
    protected ?string $schemaConfigFilename = null;
    protected bool $ignoreNonExistingSchemaFolder = false;
    /**
     * @var string[]
     */
    protected array $classPluginCollection = [
        ToArrayClassPlugin::class,
        FromArrayClassPlugin::class,
        IsNewClassPlugin::class,
        ListModifiedPropertiesClassPlugin::class,
        RequireAllClassPlugin::class,
        ImplementClassPlugin::class,
        ExtendClassPlugin::class,
        MetadataClassPlugin::class,
        UpdateMapClassPlugin::class,
        PopoMethodClassPlugin::class,
        DateTimeMethodClassPlugin::class,
    ];
    /**
     * @var string[]
     */
    protected array $propertyPluginCollection = [
        DefinePropertyPlugin::class,
        SetPropertyMethodPlugin::class,
        GetPropertyMethodPlugin::class,
        RequirePropertyMethodPlugin::class,
        HasPropertyMethodPlugin::class,
        AddItemPropertyMethodPlugin::class,
    ];

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setNamespace(?string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getNamespaceRoot(): ?string
    {
        return $this->namespaceRoot;
    }

    public function setNamespaceRoot(?string $namespaceRoot): self
    {
        $this->namespaceRoot = $namespaceRoot;

        return $this;
    }

    public function getOutputPath(): ?string
    {
        return $this->outputPath;
    }

    public function setOutputPath(?string $outputPath): self
    {
        $this->outputPath = $outputPath;

        return $this;
    }

    public function setSchemaPath(string $schemaPath): self
    {
        $this->schemaPath = $schemaPath;

        return $this;
    }

    public function getSchemaPath(): string
    {
        return $this->schemaPath;
    }

    public function getSchemaPathFilter(): ?string
    {
        return $this->schemaPathFilter;
    }

    public function setSchemaPathFilter(?string $schemaPathFilter): self
    {
        $this->schemaPathFilter = $schemaPathFilter;

        return $this;
    }

    public function getSchemaFilenameMask(): ?string
    {
        return $this->schemaFilenameMask;
    }

    public function setSchemaFilenameMask(string $schemaFilenameMask): self
    {
        $this->schemaFilenameMask = $schemaFilenameMask;

        return $this;
    }

    public function getSchemaConfigFilename(): ?string
    {
        return $this->schemaConfigFilename;
    }

    public function setSchemaConfigFilename(?string $schemaConfigFilename): self
    {
        $this->schemaConfigFilename = $schemaConfigFilename;

        return $this;
    }

    public function isIgnoreNonExistingSchemaFolder(): bool
    {
        return $this->ignoreNonExistingSchemaFolder;
    }

    public function setIgnoreNonExistingSchemaFolder(bool $ignoreNonExistingSchemaFolder): self
    {
        $this->ignoreNonExistingSchemaFolder = $ignoreNonExistingSchemaFolder;

        return $this;
    }

    public function getClassPluginCollection(): array
    {
        return $this->classPluginCollection;
    }

    public function setClassPluginCollection(array $plugins): self
    {
        $this->classPluginCollection = $plugins;

        return $this;
    }

    public function addClassPluginClass(string $pluginClassName): self
    {
        $this->classPluginCollection[] = $pluginClassName;

        return $this;
    }

    public function getPropertyPluginCollection(): array
    {
        return $this->propertyPluginCollection;
    }

    public function setPropertyPluginCollection(array $propertyPluginCollection): self
    {
        $this->propertyPluginCollection = $propertyPluginCollection;

        return $this;
    }

    public function addPropertyMethodPluginClass(string $pluginClassName): self
    {
        $this->propertyPluginCollection[] = $pluginClassName;

        return $this;
    }
}
