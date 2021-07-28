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
    protected ?string $schemaFilename = '*.popo.yml';
    protected ?string $schemaConfigFilename = null;

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

    public function getSchemaFilename(): ?string
    {
        return $this->schemaFilename;
    }

    public function setSchemaFilename(?string $schemaFilename): self
    {
        $this->schemaFilename = $schemaFilename;

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
}
