<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;
use Popo\Schema\Reader\SchemaInterface;

class PropertyNameGeneratorPlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<PROPERTY_NAME>>';

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        return $property->getName();
    }
}
