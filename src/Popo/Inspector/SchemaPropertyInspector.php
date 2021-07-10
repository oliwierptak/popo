<?php

declare(strict_types = 1);

namespace Popo\Inspector;

use JetBrains\PhpStorm\Pure;
use Popo\PopoDefinesInterface;
use Popo\Schema\Property;
use Popo\Schema\PropertySchema;
use Popo\Schema\Schema;

class SchemaPropertyInspector
{
    public function generatePopoType(Schema $schema, Property $property): string
    {
        if ($this->isPopoProperty($property->getSchema()->getType())) {
            $namespace = $this->expandNamespaceForParameter(
                $schema,
                $property->getSchema()
            );

            return sprintf(
                '%s\\%s',
                $namespace,
                str_replace('::class', '', $property->getSchema()->getDefault())
            );
        }

        return $property->getSchema()->getType();
    }

    #[Pure] public function isPopoProperty(string $type): bool
    {
        return $type === PopoDefinesInterface::PROPERTY_TYPE_POPO;
    }

    #[Pure] public function expandNamespaceForParameter(Schema $schema, PropertySchema $propertySchema): string
    {
        return sprintf(
            '\\%s',
            $schema->getNamespace()
        );
    }
}
