<?php declare(strict_types = 1);

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
    protected const WITH_POPO = 'withPopo';
    protected const WITH_INTERFACE = 'withInterface';
    protected const NAMESPACE_WITH_INTERFACE = 'namespaceWithInterface';
    protected const PARENT = 'parent';

    protected array $data;

    protected array $defaults = [
        self::NAME => '',
        self::SCHEMA => [],
        self::IS_ABSTRACT => false,
        self::EXTENDS => '',
        self::EXTENSION => '.php',
        self::RETURN_TYPE => null,
        self::WITH_POPO => true,
        self::WITH_INTERFACE => false,
        self::NAMESPACE_WITH_INTERFACE => null,
        self::PARENT => null,
    ];

    public function __construct(array $data = [])
    {
        $this->data = array_merge($this->defaults, $data);
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
        return (bool) $this->data[static::IS_ABSTRACT];
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

    public function getReturnType(): ?string
    {
        return $this->data[static::RETURN_TYPE];
    }

    public function setReturnType(?string $returnType): self
    {
        $this->data[static::RETURN_TYPE] = $returnType;

        return $this;
    }

    public function isWithIPopo(): bool
    {
        return $this->data[static::WITH_POPO];
    }

    public function setIsWithPopo(bool $isWithPopo): self
    {
        $this->data[static::WITH_POPO] = $isWithPopo;

        return $this;
    }

    public function isWithInterface(): bool
    {
        return $this->data[static::WITH_INTERFACE];
    }

    public function setIsWithInterface(bool $isWithInterface): self
    {
        $this->data[static::WITH_INTERFACE] = $isWithInterface;

        return $this;
    }

    public function getParent(): ?Schema
    {
        return $this->data[static::PARENT];
    }

    public function setParent(?Schema $parent): self
    {
        $this->data[static::PARENT] = $parent;

        return $this;
    }

    public function getClassName(): string
    {
        $nameTokens = explode('\\', $this->getName());

        $name = array_pop($nameTokens);

        return $name;
    }

    public function getName(): string
    {
        return $this->data[static::NAME];
    }

    public function getNamespaceName(): string
    {
        $nameTokens = explode('\\', $this->getName());

        array_pop($nameTokens);
        $namespace = implode('\\', $nameTokens);

        return $namespace;
    }

    public function setNamespaceWithInterface(?string $name): self
    {
        $this->data[static::NAMESPACE_WITH_INTERFACE] = $name;

        return $this;
    }

    public function getNamespaceWithInterface(): ?string
    {
        return $this->data[static::NAMESPACE_WITH_INTERFACE];
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
