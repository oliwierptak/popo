<?php

declare(strict_types = 1);

namespace Popo\Schema\Reader;

use function array_key_exists;
use function array_merge;

class Property implements PropertyInterface
{
    const NAME = 'name';
    const TYPE = 'type';
    const DEFAULT = 'default';
    const DOCBLOCK = 'docblock';
    const COLLECTION_ITEM = 'collectionItem';
    const SINGULAR = 'singular';

    /**
     * @var array
     */
    protected $defaults = [
        self::NAME => '',
        self::TYPE => '',
        self::DEFAULT => null,
        self::DOCBLOCK => '',
        self::COLLECTION_ITEM => '',
        self::SINGULAR => '',
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
        $this->data = array_merge($this->defaults, $propertySchema);
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

    public function getCollectionItem(): string
    {
        return $this->data[static::COLLECTION_ITEM];
    }

    public function getSingular(): string
    {
        return $this->data[static::SINGULAR];
    }

    public function isCollectionItem(): bool
    {
        $definitions = $this->getSchemaDefinition();
        if (!array_key_exists(static::COLLECTION_ITEM, $definitions)) {
            return false;
        }

        return trim($definitions[static::COLLECTION_ITEM]) !== '';
    }

    public function hasDefault(): bool
    {
        return array_key_exists(static::DEFAULT, $this->getSchemaDefinition());
    }

    public function hasConstantValue(): bool
    {
        return trim($this->getDefault()[0] ?? '') === '\\';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
