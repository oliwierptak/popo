<?php

declare(strict_types = 1);

namespace Popo\Inspector;

use JetBrains\PhpStorm\Pure;
use Popo\PopoDefinesInterface;
use Popo\Schema\Property;
use Popo\Schema\Schema;

class SchemaPropertyInspector
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

    #[Pure] public function isPopoProperty(string $type): bool
    {
        return $type === PopoDefinesInterface::PROPERTY_TYPE_POPO;
    }

    public function expandNamespaceForParameter(Schema $schema): string
    {
        return sprintf(
            '\\%s',
            $schema->getConfig()->getNamespace()
        );
    }
}
