<?php

declare(strict_types = 1);

namespace Popo\Schema\Property;

use Popo\PopoDefinesInterface;

class Property
{
    protected string $name;
    protected string $type = PopoDefinesInterface::PROPERTY_TYPE_STRING;
    protected ?string $comment = null;
    protected ?string $itemType = null;
    protected ?string $itemName = null;
    protected mixed $default = null;
    protected mixed $extra = null;
    protected ?string $attribute = null;

    /**
     * @var array<string, mixed>
     */
    protected array $attributes = [];
    /**
     * @var array<string>
     */
    protected array $mappingPolicy = ['\Popo\Plugin\MappingPolicy\NoneMappingPolicyPlugin::MAPPING_POLICY_NAME'];
    protected ?string $mappingPolicyValue = null;

    /**
     * @return array{name: string, type: string, comment: string|null, itemType: string|null, itemName: string|null, default: mixed, extra: mixed|null, attribute: string|null, attributes: array, mappingPolicy: array, mappingPolicyValue: string|null}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'comment' => $this->comment,
            'itemType' => $this->itemType,
            'itemName' => $this->itemName,
            'default' => $this->default,
            'extra' => $this->extra,
            'attribute' => $this->attribute,
            'attributes' => $this->attributes,
            'mappingPolicy' => $this->mappingPolicy,
            'mappingPolicyValue' => $this->mappingPolicyValue,
        ];
    }

    /**
     * @param array{name: string, type: string|null, comment: string|null, itemType: string|null, itemName: string|null, default: mixed, extra: mixed, attribute: string|null, attributes: array, mappingPolicy: array, mappingPolicyValue: string|null} $data
     *
     * @return $this
     */
    public function fromArray(array $data): self
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
        $this->extra = $data['extra'];
        $this->attribute = $data['attribute'];
        $this->attributes = $data['attributes'] ?? [];
        $this->mappingPolicy = $data['mappingPolicy'];
        $this->mappingPolicyValue = $data['mappingPolicyValue'];

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

    public function setType(string $type): self
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

    public function getExtra(): mixed
    {
        return $this->extra;
    }

    public function setExtra(mixed $extra): self
    {
        $this->extra = $extra;

        return $this;
    }

    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    public function setAttribute(?string $attribute): self
    {
        $this->attribute = $attribute;
        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getMappingPolicy(): array
    {
        return $this->mappingPolicy;
    }

    /**
     * @param string[] $mappingPolicy
     */
    public function setMappingPolicy(array $mappingPolicy): self
    {
        $this->mappingPolicy = $mappingPolicy;

        return $this;
    }

    public function getMappingPolicyValue(): ?string
    {
        return $this->mappingPolicyValue;
    }

    public function setMappingPolicyValue(?string $mappingPolicyValue): self
    {
        $this->mappingPolicyValue = $mappingPolicyValue;

        return $this;
    }
}
