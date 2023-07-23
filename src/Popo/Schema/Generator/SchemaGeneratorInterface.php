<?php

declare(strict_types = 1);

namespace Popo\Schema\Generator;

use Nette\PhpGenerator\Attribute;
use Popo\Schema\Property\Property;
use Popo\Schema\Schema;

interface SchemaGeneratorInterface
{
    public function generateDefaultTypeValue(Property $property): mixed;

    public function generatePopoType(Schema $schema, Property $property, bool $stripClass = true): string;

    public function generatePopoItemType(Schema $schema, Property $property): string;

    /**
     * @param array<string, mixed> $attributes
     *
     * @return array<Attribute>
     */
    public function parseAttributes(?string $attribute, array $attributes): array;
}
