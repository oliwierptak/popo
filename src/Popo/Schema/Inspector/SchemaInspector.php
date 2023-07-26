<?php

declare(strict_types = 1);

namespace Popo\Schema\Inspector;

use Popo\PopoDefinesInterface;
use Popo\Schema\Property\Property;
use function in_array;

class SchemaInspector implements SchemaInspectorInterface
{
    public function isPopoProperty(string $type): bool
    {
        return $type === PopoDefinesInterface::PROPERTY_TYPE_POPO;
    }

    public function isDateTimeProperty(string $type): bool
    {
        return $type === PopoDefinesInterface::PROPERTY_TYPE_DATETIME;
    }

    public function isArray(string $type): bool
    {
        return $type === PopoDefinesInterface::PROPERTY_TYPE_ARRAY;
    }

    public function isBool(string $type): bool
    {
        return $type === PopoDefinesInterface::PROPERTY_TYPE_BOOL;
    }

    public function isArrayOrMixed(string $type): bool
    {
        return in_array($type, [
            PopoDefinesInterface::PROPERTY_TYPE_ARRAY,
            PopoDefinesInterface::PROPERTY_TYPE_MIXED,
        ]);
    }

    public function isPropertyNullable(Property $property): bool
    {
        if ($this->isArrayOrMixed($property->getType())) {
            return false;
        }

        if ($this->isBool($property->getType())) {
            return false;
        }

        return true;
    }

    public function isLiteral(mixed $value): bool
    {
        if (is_string($value) === false) {
            return false;
        }

        if (trim($value) === '') {
            return false;
        }

        return str_contains($value, '::') || $value[0] === '\\';
    }

    public function hasExtra(Property $property): bool
    {
        return !empty($property->getExtra());
    }

    public function hasDefault(Property $property): bool
    {
        return !empty($property->getDefault());
    }
}
