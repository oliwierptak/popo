<?php

declare(strict_types = 1);

namespace Popo\Schema\Generator;

use Popo\Schema\Inspector\SchemaInspectorInterface;
use Popo\Schema\Property\Property;
use Popo\Schema\Schema;

class SchemaGenerator implements SchemaGeneratorInterface
{
    protected SchemaInspectorInterface $schemaInspector;

    public function __construct(SchemaInspectorInterface $schemaInspector)
    {
        $this->schemaInspector = $schemaInspector;
    }

    public function generatePopoType(Schema $schema, Property $property, bool $stripClass = true): string
    {
        if ($this->schemaInspector->isPopoProperty($property->getType())) {
            $namespace = $this->expandNamespaceForParameter($schema);

            $value = (string)$property->getDefault();
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

        if ($this->schemaInspector->isDateTimeProperty($property->getType())) {
            return '\DateTime';
        }

        return $property->getType();
    }

    public function generatePopoItemType(Schema $schema, Property $property): string
    {
        if ($this->schemaInspector->isLiteral($property->getItemType())) {
            $namespace = $this->expandNamespaceForParameter($schema);

            $value = (string) $property->getItemType();
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

        return (string) $property->getItemType();
    }

    protected function expandNamespaceForParameter(Schema $schema): string
    {
        return sprintf(
            '\\%s',
            ltrim($schema->getConfig()->getNamespace(), '\\')
        );
    }
}
