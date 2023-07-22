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
    ];

    protected string $namespace;
    protected string $outputPath;
    protected ?string $namespaceRoot = null;
    protected ?string $extend = null;
    protected ?string $implement = null;
    protected ?string $comment = null;
    protected ?string $phpComment = null;

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

    /**
     * @param array{namespace: string, namespaceRoot: string|null, outputPath: string, extend: string|null, implement: string|null, comment: string|null, phpComment: string|null} $data
     *
     * @return $this
     */
    public function fromArray(
        array $data
    ): self {
        $data = array_merge(
            [
                'namespaceRoot' => null,
                'extend' => null,
                'implement' => null,
                'comment' => null,
                'phpComment' => null,
            ],
            $data
        );

        $this->namespace = (string) $data['namespace'];
        $this->namespaceRoot = $data['namespaceRoot'];
        $this->outputPath = (string) $data['outputPath'];
        $this->extend = $data['extend'];
        $this->implement = $data['implement'];
        $this->comment = $data['comment'];
        $this->phpComment = $data['phpComment'];

        return $this;
    }

    /**
     * @return array{namespace: string, namespaceRoot: string|null, outputPath: string, extend: string|null, implement: string|null, comment: string|null, phpComment: string|null}
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
        ];
    }
}
