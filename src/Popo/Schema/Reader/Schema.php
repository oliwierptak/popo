<?php

declare(strict_types = 1);

namespace Popo\Schema\Reader;

use function array_merge;
use function array_pop;
use function explode;
use function implode;

class Schema implements SchemaInterface
{
    const NAME = 'name';
    const SCHEMA = 'schema';
    const IS_ABSTRACT = 'abstract';
    const EXTENDS = 'extends';

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
    ];

    public function __construct(array $data = [])
    {
        $this->data = array_merge($this->defaults, $data);
    }

    public function getName(): string
    {
        return $this->data[static::NAME];
    }

    public function setName(string $name): SchemaInterface
    {
        $this->data[static::NAME] = $name;

        return $this;
    }

    public function getSchema(): array
    {
        return $this->data[static::SCHEMA];
    }

    public function setSchema(array $schema): SchemaInterface
    {
        $this->data[static::SCHEMA] = $schema;

        return $this;
    }

    public function isAbstract(): bool
    {
        return (bool) $this->data[static::IS_ABSTRACT];
    }

    public function setIsAbstract(bool $isAbstract): SchemaInterface
    {
        $this->data[static::IS_ABSTRACT] = $isAbstract;

        return $this;
    }

    public function getExtends(): string
    {
        return $this->data[static::EXTENDS];
    }

    public function setExtends(string $extends): SchemaInterface
    {
        $this->data[static::EXTENDS] = $extends;

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
