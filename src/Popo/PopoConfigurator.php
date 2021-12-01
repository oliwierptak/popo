<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Plugin\FromArrayPlugin;
use Popo\Plugin\IsNewPlugin;
use Popo\Plugin\ListModifiedPropertiesPlugin;
use Popo\Plugin\RequireAllPlugin;
use Popo\Plugin\ToArrayPlugin;

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
    protected array $pluginClasses = [
        ToArrayPlugin::class,
        FromArrayPlugin::class,
        IsNewPlugin::class,
        ListModifiedPropertiesPlugin::class,
        RequireAllPlugin::class
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

    public function getPluginClasses(): array
    {
        return $this->pluginClasses;
    }

    public function setPluginClasses(array $plugins): self
    {
        $this->pluginClasses = $plugins;

        return $this;
    }

    public function addPluginClass(string $pluginClassName): self
    {
        $this->pluginClasses[] = $pluginClassName;

        return $this;
    }
}
