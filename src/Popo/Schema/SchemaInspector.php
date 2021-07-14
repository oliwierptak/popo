<?php

declare(strict_types = 1);

namespace Popo\Schema;

use JetBrains\PhpStorm\Pure;
use Popo\PopoDefinesInterface;

class SchemaInspector
{
    public function generatePopoType(Schema $schema, Property $property, bool $stripClass = true): string
    {
        if ($this->isPopoProperty($property->getType())) {
            $namespace = $this->expandNamespaceForParameter($schema);

            $value = $property->getDefault();
            $class = sprintf(
                '%s',
                $stripClass ? str_replace('::class', '', $value) : $value
            );

            if ($value[0] !== '\\') {
                $class = sprintf(
                    '%s\\%s',
                    $namespace,
                    $stripClass ? str_replace('::class', '', $value) : $value
                );
            }

            return $class;
        }

        return $property->getType();
    }

    public function generatePopoItemType(Schema $schema, Property $property, bool $stripClass = true): string
    {
        if ($this->isLiteral($property->getItemType())) {
            $namespace = $this->expandNamespaceForParameter($schema);

            $value = $property->getItemType();
            $class = sprintf(
                '%s',
                $stripClass ? str_replace('::class', '', $value) : $value
            );

            if ($value[0] !== '\\') {
                $class = sprintf(
                    '%s\\%s',
                    $namespace,
                    $stripClass ? str_replace('::class', '', $value) : $value
                );
            }

            return $class;
        }

        return $property->getItemType();
    }

    protected function expandNamespaceForParameter(Schema $schema): string
    {
        return sprintf(
            '\\%s',
            ltrim($schema->getConfig()->getNamespace(), '\\')
        );
    }

    #[Pure] public function isPopoProperty(string $type): bool
    {
        return $type === PopoDefinesInterface::PROPERTY_TYPE_POPO;
    }

    #[Pure] public function isArray(string $type): bool
    {
        return $type === PopoDefinesInterface::PROPERTY_TYPE_ARRAY;
    }

    #[Pure] public function isArrayOrMixed(string $type): bool
    {
        return $type === PopoDefinesInterface::PROPERTY_TYPE_ARRAY ||
            $type === PopoDefinesInterface::PROPERTY_TYPE_MIXED;
    }

    #[Pure] public function isLiteral(mixed $value): bool
    {
        if (is_string($value) === false) {
            return false;
        }

        return strpos($value, '::') !== false || strpos($value, '::class') !== false;
    }
}
