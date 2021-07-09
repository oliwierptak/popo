<?php

declare(strict_types = 1);

namespace Popo\Schema;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\ExpectedValues;

class PropertySchema
{
    protected const PROPERTY_TYPE_ARRAY = 'array';

    protected const PROPERTY_TYPE_BOOL = 'bool';

    protected const PROPERTY_TYPE_FLOAT = 'float';

    protected const PROPERTY_TYPE_INT = 'int';

    protected const PROPERTY_TYPE_STRING = 'string';

    protected const PROPERTY_TYPE_MIXED = 'mixed';

    protected const PROPERTY_TYPE_CONST = 'const';

    protected const PROPERTY_TYPE_POPO = 'popo';

    protected const PROPERTY_SHAPE = [
        'name' => 'string',
        'type' => 'string',
        'docblock' => 'mixed',
        'itemType' => 'mixed',
        'itemName' => 'mixed',
        'default' => 'mixed',
    ];

    protected const EXPECTED_TYPE_VALUES = [
        'array',
        'bool',
        'float',
        'int',
        'string',
        'mixed',
        'const',
        'popo',
    ];

    protected string $name;
    protected string $type = 'string';
    protected ?string $docblock;
    protected ?string $itemType;
    protected ?string $itemName;
    protected mixed $default;

    #[ArrayShape(self::PROPERTY_SHAPE)]
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'docblock' => $this->docblock,
            'itemType' => $this->itemType,
            'itemName' => $this->itemName,
            'default' => $this->default,
        ];
    }

    public function fromArray(
        #[ArrayShape(self::PROPERTY_SHAPE)]
        array $data): self
    {
        $data = array_merge(
            [
                'name' => null,
                'type' => 'string',
                'docblock' => null,
                'itemType' => null,
                'itemName' => null,
                'default' => null,
            ],
            $data
        );

        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->docblock = $data['docblock'];
        $this->itemType = $data['itemType'];
        $this->itemName = $data['itemName'];
        $this->default = $data['default'];

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(
        #[ExpectedValues(self::EXPECTED_TYPE_VALUES)]
        string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDocblock(): ?string
    {
        return $this->docblock;
    }

    public function setDocblock(?string $docblock): self
    {
        $this->docblock = $docblock;

        return $this;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }

    public function setDefault(mixed $default): self
    {
        $this->default = $default;

        return $this;
    }

    public function getItemType(): ?string
    {
        return $this->itemType;
    }

    public function setItemType(?string $itemType): self
    {
        $this->itemType = $itemType;

        return $this;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(?string $itemName): self
    {
        $this->itemName = $itemName;

        return $this;
    }
}
