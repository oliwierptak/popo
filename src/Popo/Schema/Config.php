<?php

declare(strict_types = 1);

namespace Popo\Schema;

use JetBrains\PhpStorm\ArrayShape;

class Config
{
    protected const CONFIG_SHAPE = [
        'namespace' => "string",
        'extend' => "null|string",
        'implement' => "null|string",
        'outputPath' => "null|string",
        'schemaPath' => "null|string",
        'default' => "array",
    ];

    protected string $namespace;
    protected ?string $extend;
    protected ?string $implement;
    protected ?string $outputPath;
    protected ?string $schemaPath;
    protected array $default = [];

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

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

    public function getOutputPath(): ?string
    {
        return $this->outputPath;
    }

    public function setOutputPath(?string $outputPath): self
    {
        $this->outputPath = $outputPath;

        return $this;
    }

    public function getSchemaPath(): ?string
    {
        return $this->schemaPath;
    }

    public function setSchemaPath(?string $schemaPath): self
    {
        $this->schemaPath = $schemaPath;

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

    public function fromArray(
        #[ArrayShape(self::CONFIG_SHAPE)]
        array $data
    ): self {
        $data = array_merge(
            [
                'extend' => null,
                'implement' => null,
                'outputPath' => null,
                'schemaPath' => null,
                'default' => [],
            ],
            $data
        );

        $this->namespace = $data['namespace'];
        $this->extend = $data['extend'] ?? null;
        $this->implement = $data['implement'] ?? null;
        $this->outputPath = $data['outputPath'] ?? null;
        $this->schemaPath = $data['schemaPath'] ?? null;
        $this->default = $data['default'] ?? [];

        return $this;
    }

    #[ArrayShape(self::CONFIG_SHAPE)]
    public function toArray(): array
    {
        return [
            'namespace' => $this->namespace,
            'extend' => $this->extend,
            'implement' => $this->implement,
            'outputPath' => $this->outputPath ?? null,
            'schemaPath' => $this->schemaPath ?? null,
            'default' => $this->default ?? [],
        ];
    }
}
