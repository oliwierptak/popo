<?php

declare(strict_types = 1);

namespace Popo\Schema;

use JetBrains\PhpStorm\ArrayShape;

class Config
{
    protected const CONFIG_SHAPE = [
        'namespace' => "string",
        'outputPath' => "string",
        'extend' => "null|string",
        'implement' => "null|string",
        'default' => "array",
        'defaultConfig' => Config::class,
    ];

    protected string $namespace;
    protected string $outputPath;
    protected ?string $extend;
    protected ?string $implement;
    protected array $default = [];
    protected Config $defaultConfig;

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

    public function getExtend(): ?string
    {
        return $this->extend;
    }

    public function setExtend(?string $extend): self
    {
        $this->extend = $extend;

        return $this;
    }

    public function getImplement(): ?string
    {
        return $this->implement;
    }

    public function setImplement(?string $implement): self
    {
        $this->implement = $implement;

        return $this;
    }

    public function getDefault(): array
    {
        return $this->default;
    }

    public function setDefault(array $default): self
    {
        $this->default = $default;

        return $this;
    }

    public function getDefaultConfig(): Config
    {
        if (empty($this->defaultConfig)) {
            $this->defaultConfig = new Config();
        }

        return $this->defaultConfig;
    }

    public function setDefaultConfig(Config $defaultConfig): self
    {
        $this->defaultConfig = $defaultConfig;

        return $this;
    }

    public function fromArray(
        #[ArrayShape(self::CONFIG_SHAPE)]
        array $data
    ): self {
        $data = array_merge(
            [
                'extend' => null,
                'implement' => null,
                'default' => [],
            ],
            $data
        );

        $this->namespace = $data['namespace'];
        $this->outputPath = $data['outputPath'];
        $this->extend = $data['extend'] ?? null;
        $this->implement = $data['implement'] ?? null;
        $this->default = $data['default'] ?? [];
        $this->defaultConfig = $this->getDefaultConfig();

        return $this;
    }

    #[ArrayShape(self::CONFIG_SHAPE)]
    public function toArray(): array
    {
        return [
            'namespace' => $this->namespace,
            'outputPath' => $this->outputPath,
            'extend' => $this->extend,
            'implement' => $this->implement,
            'default' => $this->default ?? [],
            'defaultConfig' => $this->getDefaultConfig(),
        ];
    }
}
