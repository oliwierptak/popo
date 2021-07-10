<?php

declare(strict_types = 1);

namespace Popo\Inspector;

use JetBrains\PhpStorm\Pure;
use Popo\PopoDefinesInterface;
use Popo\Schema\PropertySchema;
use Popo\Schema\Schema;

class SchemaPropertyInspector
{
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
