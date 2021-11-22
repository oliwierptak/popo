<?php

declare(strict_types = 1);

namespace Popo\Schema;

use JetBrains\PhpStorm\ArrayShape;
use Popo\PopoDefinesInterface;

class Property
{
    protected const PROPERTY_SHAPE = [
        'name' => 'string',
        'type' => 'string',
        'comment' => 'string|null',
        'itemType' => 'string|null',
        'itemName' => 'string|null',
        'default' => '',
    ];

    protected const EXPECTED_TYPE_VALUES = [
        'array',
        'bool',
        'float',
        'int',
        'string',
        '',
        'const',
        'popo',
    ];

    protected string $name;
    protected string $type = PopoDefinesInterface::PROPERTY_TYPE_STRING;
    protected ?string $comment = null;
    protected ?string $itemType = null;
    protected ?string $itemName = null;
    /** @var mixed */
    protected $default = null;

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
        array $data
    ): self {
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(

        string $type
    ): self {
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

    /**
     * @return mixed|null
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed|null $default
     *
     * @return $this
     */
    public function setDefault($default): self
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
