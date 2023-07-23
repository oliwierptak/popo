<?php

declare(strict_types = 1);

namespace Popo\Schema\Config;

class Config
{
    protected const CONFIG_SHAPE = [
        'namespace' => "null|string",
        'outputPath' => "null|string",
        'namespaceRoot' => "null|string",
        'extend' => "null|string",
        'implement' => "null|string",
        'comment' => "null|string",
        'phpComment' => "null|string",
        'use' => "array<string>|[]",
        'attribute' => "string|null",
        'attributes' => "array<string,mixed>|[]",
    ];

    protected string $namespace;
    protected string $outputPath;
    protected ?string $namespaceRoot = null;
    protected ?string $extend = null;
    protected ?string $implement = null;
    protected ?string $comment = null;
    protected ?string $phpComment = null;
    /**
     * @var array<string>
     */
    protected array $use = [];
    protected ?string $attribute = null;

    /**
     * @var array<string, mixed>
     */
    protected array $attributes = [];

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

    public function getPhpComment(): ?string
    {
        return $this->phpComment;
    }

    public function setPhpComment(?string $phpComment): self
    {
        $this->phpComment = $phpComment;

        return $this;
    }

    public function getUse(): array
    {
        return $this->use;
    }

    public function setUse(array $use): self
    {
        $this->use = $use;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    public function setAttribute(?string $attribute): self
    {
        $this->attribute = $attribute;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @param array{namespace: string, namespaceRoot: string|null, outputPath: string, extend: string|null, implement: string|null, comment: string|null, phpComment: string|null, use: array, attribute: string|null, attributes: array} $data
     *
     * @return $this
     */
    public function fromArray(
        array $data,
    ): self
    {
        $data = array_merge(
            [
                'namespaceRoot' => null,
                'extend' => null,
                'implement' => null,
                'comment' => null,
                'phpComment' => null,
                'use' => [],
                'attribute' => null,
                'attributes' => [],
            ],
            $data,
        );

        $this->namespace = (string)$data['namespace'];
        $this->namespaceRoot = $data['namespaceRoot'];
        $this->outputPath = (string)$data['outputPath'];
        $this->extend = $data['extend'];
        $this->implement = $data['implement'];
        $this->comment = $data['comment'];
        $this->phpComment = $data['phpComment'];
        $this->use = $data['use'];
        $this->attribute = $data['attribute'];
        $this->attributes = $data['attributes'];

        return $this;
    }

    /**
     * @return array{namespace: string, namespaceRoot: string|null, outputPath: string, extend: string|null, implement: string|null, comment: string|null, phpComment: string|null, use: array, attribute: string|null, attributes: array}
     */
    public function toArray(): array
    {
        return [
            'namespace' => $this->namespace,
            'namespaceRoot' => $this->namespaceRoot,
            'outputPath' => $this->outputPath,
            'extend' => $this->extend,
            'implement' => $this->implement,
            'comment' => $this->comment,
            'phpComment' => $this->phpComment,
            'use' => $this->use,
            'attribute' => $this->attribute,
            'attributes' => $this->attributes,
        ];
    }
}
