<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Getter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\Reader\PropertyInterface;

class GetMethodNameGeneratorPlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<GET_METHOD_NAME>>';

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        $name = $property->getName();

        if ($this->propertyExplorer->isBoolean($property->getType())) {
            return $name;
        }

        return 'get' . \ucfirst($name);
    }
}
