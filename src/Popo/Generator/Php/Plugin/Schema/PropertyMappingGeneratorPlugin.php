<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\PropertyInterface;
use Popo\Schema\Reader\SchemaInterface;
use function var_export;

class PropertyMappingGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<PROPERTY_MAPPING>>';

    public function generate(SchemaInterface $schema): string
    {
        $schemaKeys = [];

        foreach ($schema->getSchema() as $propertyData) {
            $property = $this->buildProperty($schema, $propertyData);
            $schemaKeys[$property->getName()] = $property->getType();
        }

        return var_export($schemaKeys, true);
    }

    protected function buildProperty(SchemaInterface $schema, array $propertyData): PropertyInterface
    {
        return new Property($schema, $propertyData);
    }
}
