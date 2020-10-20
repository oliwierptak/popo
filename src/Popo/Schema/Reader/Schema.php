<?php

declare(strict_types = 1);

namespace Popo\Schema\Reader;

use function array_merge;
use function array_pop;
use function explode;
use function implode;

class Schema
{
    protected const NAME = 'name';
    protected const SCHEMA = 'schema';
    protected const IS_ABSTRACT = 'abstract';
    protected const EXTENDS = 'extends';
    protected const EXTENSION = 'extension';
    protected const RETURN_TYPE = 'returnType';
    protected const WITH_INTERFACE = 'withInterface';

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $defaults = [
        self::NAME => '',
        self::SCHEMA => [],
        self::IS_ABSTRACT => false,
        self::EXTENDS => '',
        self::EXTENSION => '.php',
        self::RETURN_TYPE => 'self',
        self::WITH_INTERFACE => false,
    ];

    public function __construct(array $data = [])
    {
        $this->data = array_merge($this->defaults, $data);
    }

    public function getName(): string
    {
        return $this->data[static::NAME];
    }

    public function setName(string $name): self
    {
        $this->data[static::NAME] = $name;

        return $this;
    }

    public function getSchema(): array
    {
        return $this->data[static::SCHEMA];
    }

    public function setSchema(array $schema): self
    {
        $this->data[static::SCHEMA] = $schema;

        return $this;
    }

    public function isAbstract(): bool
    {
        return (bool)$this->data[static::IS_ABSTRACT];
    }

    public function setIsAbstract(bool $isAbstract): self
    {
        $this->data[static::IS_ABSTRACT] = $isAbstract;

        return $this;
    }

    public function getExtends(): string
    {
        return $this->data[static::EXTENDS];
    }

    public function setExtends(string $extends): self
    {
        $this->data[static::EXTENDS] = $extends;

        return $this;
    }

    public function getExtension(): string
    {
        return $this->data[static::EXTENSION];
    }

    public function setExtension(string $extension): self
    {
        $this->data[static::EXTENSION] = $extension;

        return $this;
    }

    public function getReturnType(): string
    {
        return $this->data[static::RETURN_TYPE];
    }

    public function setReturnType(string $returnType): self
    {
        $this->data[static::RETURN_TYPE] = $returnType;

        return $this;
    }

    public function getWithInterface(): bool
    {
        return $this->data[static::WITH_INTERFACE];
    }

    public function setWithInterface(bool $withInterface): self
    {
        $this->data[static::WITH_INTERFACE] = $withInterface;

        return $this;
    }

    public function getClassName(): string
    {
        $nameTokens = explode('\\', $this->getName());

        $name = array_pop($nameTokens);

        return $name;
    }

    public function getNamespaceName(): string
    {
        $nameTokens = explode('\\', $this->getName());

        array_pop($nameTokens);
        $namespace = implode('\\', $nameTokens);

        return $namespace;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
