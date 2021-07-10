<?php

declare(strict_types = 1);

namespace Popo;

class PopoConfigurator
{
    protected string $namespace;
    protected string $outputPath;
    protected string $configFile;

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): self
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

    public function setConfigFile(string $configFile): self
    {
        $this->configFile = $configFile;

        return $this;
    }

    public function getConfigFile(): string
    {
        return $this->configFile;
    }
}
