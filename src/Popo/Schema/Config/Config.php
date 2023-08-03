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
        'trait' => "array<string>|[]",
        'attribute' => "string|null",
        'attributes' => "array<string,mixed>|[]",
        'classPluginCollection' => "array<string,mixed>|[]",
        'phpFilePluginCollection' => "array<string,mixed>|[]",
        'namespacePluginCollection' => "array<string,mixed>|[]",
        'propertyPluginCollection' => "array<string,mixed>|[]",
        'mappingPolicyPluginCollection' => "array<string,mixed>|[]",
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
    /**
     * @var array<string>
     */
    protected array $trait = [];
    protected ?string $attribute = null;
    /**
     * @var array<string, mixed>
     */
    protected array $attributes = [];
    /**
     * @var array<string>
     */
    protected array $classPluginCollection = [];
    /**
     * @var array<string>
     */
    protected array $phpFilePluginCollection = [];
    /**
     * @var array<string>
     */
    protected array $namespacePluginCollection = [];
    /**
     * @var array<string>
     */
    protected array $propertyPluginCollection = [];
    /**
     * @var array<string>
     */
    protected array $mappingPolicyPluginCollection = [];

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
     * @return array<string>
     */
    public function getUse(): array
    {
        return $this->use;
    }

    /**
     * @param array<string> $use
     */
    public function setUse(array $use): self
    {
        $this->use = $use;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getTrait(): array
    {
        return $this->trait;
    }

    /**
     * @param array<string> $trait
     */
    public function setTrait(array $trait): self
    {
        $this->trait = $trait;

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
     * @return array<string>
     */
    public function getClassPluginCollection(): array
    {
        return $this->classPluginCollection;
    }

    /**
     * @param array<string> $classPluginCollection
     */
    public function setClassPluginCollection(array $classPluginCollection): self
    {
        $this->classPluginCollection = $classPluginCollection;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getPhpFilePluginCollection(): array
    {
        return $this->phpFilePluginCollection;
    }

    /**
     * @param array<string> $phpFilePluginCollection
     */
    public function setPhpFilePluginCollection(array $phpFilePluginCollection): self
    {
        $this->phpFilePluginCollection = $phpFilePluginCollection;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getNamespacePluginCollection(): array
    {
        return $this->namespacePluginCollection;
    }

    /**
     * @param array<string> $namespacePluginCollection
     */
    public function setNamespacePluginCollection(array $namespacePluginCollection): self
    {
        $this->namespacePluginCollection = $namespacePluginCollection;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getPropertyPluginCollection(): array
    {
        return $this->propertyPluginCollection;
    }

    /**
     * @param array<string> $propertyPluginCollection
     */
    public function setPropertyPluginCollection(array $propertyPluginCollection): self
    {
        $this->propertyPluginCollection = $propertyPluginCollection;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getMappingPolicyPluginCollection(): array
    {
        return $this->mappingPolicyPluginCollection;
    }

    /**
     * @param array<string> $mappingPolicyPluginCollection
     */
    public function setMappingPolicyPluginCollection(array $mappingPolicyPluginCollection): self
    {
        $this->mappingPolicyPluginCollection = $mappingPolicyPluginCollection;

        return $this;
    }

    /**
     * @param array{namespace: string, namespaceRoot: string|null, outputPath: string, extend: string|null, implement: string|null, comment: string|null, phpComment: string|null, use: array, trait: array, attribute: string|null, attributes: array, classPluginCollection: array, phpFilePluginCollection: array, namespacePluginCollection: array, propertyPluginCollection: array, mappingPolicyPluginCollection:array} $data
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
                'trait' => [],
                'attribute' => null,
                'attributes' => [],
                'classPluginCollection' => [],
                'phpFilePluginCollection' => [],
                'namespacePluginCollection' => [],
                'propertyPluginCollection' => [],
                'mappingPolicyPluginCollection' => [],
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
        $this->trait = $data['trait'];
        $this->attribute = $data['attribute'];
        $this->attributes = $data['attributes'];
        $this->classPluginCollection = $data['classPluginCollection'];
        $this->phpFilePluginCollection = $data['phpFilePluginCollection'];
        $this->namespacePluginCollection = $data['namespacePluginCollection'];
        $this->propertyPluginCollection = $data['propertyPluginCollection'];
        $this->mappingPolicyPluginCollection = $data['mappingPolicyPluginCollection'];

        return $this;
    }

    /**
     * @return array{namespace: string, namespaceRoot: string|null, outputPath: string, extend: string|null, implement: string|null, comment: string|null, phpComment: string|null, use: array, trait: array, attribute: string|null, attributes: array, classPluginCollection: array, phpFilePluginCollection: array, namespacePluginCollection: array, propertyPluginCollection: array, mappingPolicyPluginCollection:array}
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
            'trait' => $this->trait,
            'attribute' => $this->attribute,
            'attributes' => $this->attributes,
            'classPluginCollection' => $this->classPluginCollection,
            'phpFilePluginCollection' => $this->phpFilePluginCollection,
            'namespacePluginCollection' => $this->namespacePluginCollection,
            'propertyPluginCollection' => $this->propertyPluginCollection,
            'mappingPolicyPluginCollection' => $this->mappingPolicyPluginCollection,
        ];
    }
}
