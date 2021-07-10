<?php

declare(strict_types = 1);

namespace Popo;

class PopoConfigurator
{
    protected ?string $namespace;
    protected string $outputPath;
    protected string $schemaPath;

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
}
