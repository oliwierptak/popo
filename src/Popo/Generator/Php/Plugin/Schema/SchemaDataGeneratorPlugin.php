<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\PropertyInterface;
use Popo\Schema\Reader\SchemaInterface;

class SchemaDataGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<SCHEMA_DATA>>';

    public function generate(SchemaInterface $schema): string
    {
        $defaults = [];

        foreach ($schema->getSchema() as $propertyData) {
            $property = $this->buildProperty($schema, $propertyData);
            if (!$property->hasDefault()) {
                continue;
            }

            $defaults[$property->getName()] = $property->getDefault();
        }

        return \var_export($defaults, true);
    }

    protected function buildProperty(SchemaInterface $schema, array $propertyData): PropertyInterface
    {
        $property = new Property($schema, $propertyData);

        return $property;
    }
}
