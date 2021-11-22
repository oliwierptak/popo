<?php

declare(strict_types = 1);

namespace Popo;

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
    protected bool $php74Compatible = true;

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

    public function isPhp74Compatible(): bool
    {
        return $this->php74Compatible;
    }

    public function setPhp74Compatible(bool $php74Compatible): self
    {
        $this->php74Compatible = $php74Compatible;

        return $this;
    }
}
