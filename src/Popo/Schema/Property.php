<?php

declare(strict_types = 1);

namespace Popo\Schema;

use JetBrains\PhpStorm\ArrayShape;

class Property
{
    protected PropertySchema $schema;
    protected mixed $value = null;

    #[ArrayShape([
        'schema' => PropertySchema::class,
        'value' => 'mixed',
    ])]
    public function toArray(): array
    {
        return [
            'schema' => $this->schema,
            'value' => $this->value,
        ];
    }

    public function fromArray(
        #[ArrayShape([
            'schema' => PropertySchema::class,
            'value' => 'mixed',
        ])]
        array $data
    ): self {
        $this->schema = $data['schema'];
        $this->value = $data['value'];

        return $this;
    }

    public function getSchema(): PropertySchema
    {
        return $this->schema;
    }

    public function setSchema(PropertySchema $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }
}
