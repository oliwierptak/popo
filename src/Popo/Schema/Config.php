<?php

declare(strict_types = 1);

namespace Popo\Schema;

use JetBrains\PhpStorm\ArrayShape;

class Config
{
    protected const CONFIG_SHAPE = [
        'namespace' => "string",
        'outputPath' => "string",
        'namespaceRoot' => "null|string",
        'extend' => "null|string",
        'implement' => "null|string",
        'comment' => "string",
        'default' => "array",
        'property' => "array",
        'defaultConfig' => Config::class,
    ];

    protected string $namespace;
    protected string $outputPath;
    protected ?string $namespaceRoot = null;
    protected ?string $extend = null;
    protected ?string $implement = null;
    protected ?string $comment = null;
    protected array $default = [];
    protected array $propertyCollection = [];
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

    public function getNamespaceRoot(): ?string
    {
        return $this->namespaceRoot;
    }

    public function setNamespaceRoot(?string $namespaceRoot): self
    {
        $this->namespaceRoot = $namespaceRoot;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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

    public function getPropertyCollection(): array
    {
        return $this->propertyCollection;
    }

    public function setPropertyCollection(array $propertyCollection): self
    {
        $this->propertyCollection = $propertyCollection;

        return $this;
    }

    public function fromArray(
        #[ArrayShape(self::CONFIG_SHAPE)]
        array $data
    ): self {
        $data = array_merge(
            [
                'namespaceRoot' => null,
                'extend' => null,
                'implement' => null,
                'comment' => null,
                'default' => [],
                'property' => [],
            ],
            $data
        );

        $this->namespace = $data['namespace'];
        $this->namespaceRoot = $data['namespaceRoot'];
        $this->outputPath = $data['outputPath'];
        $this->extend = $data['extend'];
        $this->implement = $data['implement'];
        $this->comment = $data['comment'];
        $this->default = $data['default'];
        $this->propertyCollection = $data['property'];
        $this->defaultConfig = $this->getDefaultConfig();

        return $this;
    }

    #[ArrayShape(self::CONFIG_SHAPE)]
    public function toArray(): array
    {
        return [
            'namespace' => $this->namespace,
            'namespaceRoot' => $this->namespaceRoot,
            'outputPath' => $this->outputPath,
            'extend' => $this->extend,
            'implement' => $this->implement,
            'comment' => $this->comment,
            'default' => $this->default,
            'property' => $this->propertyCollection,
            'defaultConfig' => $this->getDefaultConfig(),
        ];
    }
}
