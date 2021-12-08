<?php

declare(strict_types = 1);

namespace Popo\Schema;

use Popo\PopoDefinesInterface;

class SchemaInspector implements SchemaInspectorInterface
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

    public function generatePopoItemType(Schema $schema, Property $property): string
    {
        if ($this->isLiteral($property->getItemType())) {
            $namespace = $this->expandNamespaceForParameter($schema);

            $value = $property->getItemType();
            $class = sprintf(
                '%s',
                str_replace('::class', '', $value)
            );

            if ($value[0] !== '\\') {
                $class = sprintf(
                    '%s\\%s',
                    $namespace,
                    str_replace('::class', '', $value)
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

    public function isPopoProperty(string $type): bool
    {
        return $type === PopoDefinesInterface::PROPERTY_TYPE_POPO;
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
        return $type === PopoDefinesInterface::PROPERTY_TYPE_ARRAY ||
            $type === PopoDefinesInterface::PROPERTY_TYPE_MIXED;
    }

    public function isPropertyNullable(Property $property): bool
    {
        return $this->isArrayOrMixed($property->getType()) === false ||
            $this->isPopoProperty($property->getType());
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isLiteral($value): bool
    {
        if (is_string($value) === false) {
            return false;
        }

        return strpos($value, '::') !== false || $value[0] === '\\';
    }
}
