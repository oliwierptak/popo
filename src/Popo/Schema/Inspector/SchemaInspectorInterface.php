<?php

declare(strict_types = 1);

namespace Popo\Schema\Inspector;

use Popo\Schema\Property\Property;

interface SchemaInspectorInterface
{
    public function isPopoProperty(string $type): bool;

    public function isDateTimeProperty(string $type): bool;

    public function isArray(string $type): bool;

    public function isBool(string $type): bool;

    public function isArrayOrMixed(string $type): bool;

    public function isPropertyNullable(Property $property): bool;

    public function isLiteral(mixed $value): bool;

    public function hasExtra(Property $property): bool;
}
