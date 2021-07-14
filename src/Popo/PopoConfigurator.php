<?php

declare(strict_types = 1);

namespace Popo;

class PopoConfigurator
{
    protected ?string $namespace;
    protected string $outputPath;
    protected string $schemaPath;
    protected ?string $schemaPathFilter = '';
    protected ?string $schemaFilename = '*.popo.yml';

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setNamespace(?string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getOutputPath(): string
    {
        return $this->outputPath;
    }

    public function setOutputPath(string $outputPath): self
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
}
