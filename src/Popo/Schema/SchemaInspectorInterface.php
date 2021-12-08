<?php

declare(strict_types = 1);

namespace Popo\Schema;

interface SchemaInspectorInterface
{
    public function generatePopoType(Schema $schema, Property $property, bool $stripClass = true): string;

    public function generatePopoItemType(Schema $schema, Property $property): string;

    public function isPopoProperty(string $type): bool;

    public function isArray(string $type): bool;

    public function isBool(string $type): bool;

    public function isArrayOrMixed(string $type): bool;

    public function isPropertyNullable(Property $property): bool;

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isLiteral($value): bool;
}
