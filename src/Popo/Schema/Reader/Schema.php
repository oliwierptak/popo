<?php

declare(strict_types = 1);

namespace Popo\Schema\Reader;

class Schema implements SchemaInterface
{
    const NAME = 'name';
    const SCHEMA = 'schema';

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $defaults = [
        self::NAME => '',
        self::SCHEMA => '',
    ];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data = \array_merge($this->defaults, $data);
        $this->data = $data;
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

    /**
     * @return array
     */
    public function getSchema(): array
    {
        return $this->data[static::SCHEMA];
    }

    /**
     * @param array $schema
     *
     * @return \Popo\Schema\Reader\SchemaInterface
     */
    public function setSchema(array $schema): SchemaInterface
    {
        $this->data[static::SCHEMA] = $schema;

        return $this;
    }

    public function getClassName(): string
    {
        $nameTokens = \explode('\\', $this->getName());

        $name = \array_pop($nameTokens);

        return $name;
    }

    public function getNamespaceName(): string
    {
        $nameTokens = \explode('\\', $this->getName());

        \array_pop($nameTokens);
        $namespace = \implode('\\', $nameTokens);

        return $namespace;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
