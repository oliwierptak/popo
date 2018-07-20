<?php

declare(strict_types = 1);

namespace Popo\Schema\Reader;

class Property implements PropertyInterface
{
    const NAME = 'name';
    const TYPE = 'type';
    const DEFAULT = 'default';
    const DOCBLOCK = 'docblock';

    /**
     * @var array
     */
    protected $defaults = [
        self::NAME => '',
        self::TYPE => '',
        self::DEFAULT => null,
        self::DOCBLOCK => '',
    ];

    /**
     * @var array
     */
    protected $data;

    /**
     * @var \Popo\Schema\Reader\SchemaInterface
     */
    protected $schema;

    /**
     * @var array
     */
    protected $schemaDefinition;

    /**
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     * @param array $propertySchema
     */
    public function __construct(SchemaInterface $schema, array $propertySchema)
    {
        $this->schema = $schema;
        $this->data = \array_merge($this->defaults, $propertySchema);
    }

    protected function getSchemaDefinition(): array
    {
        if ($this->schemaDefinition) {
            return $this->schemaDefinition;
        }

        foreach ($this->schema->getSchema() as $schemaItem) {
            if ($schemaItem[static::NAME] === $this->getName()) {
                $this->schemaDefinition = $schemaItem;
                break;
            }
        }

        return $this->schemaDefinition;
    }

    public function getSchema(): SchemaInterface
    {
        return $this->schema;
    }

    public function getName(): string
    {
        return $this->data[static::NAME];
    }

    /**
     * @return mixed|null
     */
    public function getDefault()
    {
        return $this->data[static::DEFAULT];
    }

    public function getDocblock(): string
    {
        return $this->data[static::DOCBLOCK];
    }

    public function getType(): string
    {
        return $this->data[static::TYPE];
    }

    public function hasDefault(): bool
    {
        return \array_key_exists(static::DEFAULT, $this->getSchemaDefinition());
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
