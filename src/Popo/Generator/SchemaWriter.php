<?php

declare(strict_types = 1);

namespace Popo\Generator;

use JetBrains\PhpStorm\Pure;
use Popo\Schema\PropertySchema;
use Popo\Schema\Schema;

class SchemaWriter
{
    #[Pure] public function expandNamespaceForParameter(Schema $schema, PropertySchema $propertySchema): string
    {
        return sprintf(
            '\\%s',
            $schema->getNamespace()
        );
    }
}
