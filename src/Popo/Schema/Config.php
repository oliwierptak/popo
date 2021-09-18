<?php

declare(strict_types = 1);

namespace Popo\Schema;

use JetBrains\PhpStorm\ArrayShape;

class Config
{
    protected const CONFIG_SHAPE = [
        'namespace' => "null|string",
        'outputPath' => "null|string",
        'namespaceRoot' => "null|string",
        'extend' => "null|string",
        'implement' => "null|string",
        'comment' => "null|string",
    ];

    protected string $namespace;
    protected string $outputPath;
    protected ?string $namespaceRoot = null;
    protected ?string $extend = null;
    protected ?string $implement = null;
    protected ?string $comment = null;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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
            ],
            $data
        );

        $this->namespace = $data['namespace'];
        $this->namespaceRoot = $data['namespaceRoot'];
        $this->outputPath = $data['outputPath'];
        $this->extend = $data['extend'];
        $this->implement = $data['implement'];
        $this->comment = $data['comment'];

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
        ];
    }
}
