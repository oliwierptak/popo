<?php

declare(strict_types = 1);

namespace Popo\Schema;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\ExpectedValues;
use Popo\PopoDefinesInterface;

class Property
{
    protected const PROPERTY_SHAPE = [
        'name' => 'string',
        'type' => 'string',
        'comment' => 'string|null',
        'itemType' => 'string|null',
        'itemName' => 'string|null',
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
    protected string $type = PopoDefinesInterface::PROPERTY_TYPE_STRING;
    protected ?string $comment = null;
    protected ?string $itemType = null;
    protected ?string $itemName = null;
    protected mixed $default = null;

    #[ArrayShape(self::PROPERTY_SHAPE)]
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'comment' => $this->comment,
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
            PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT_DATA,
            $data
        );

        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->comment = $data['comment'];
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

    #[ExpectedValues(self::EXPECTED_TYPE_VALUES)]
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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
