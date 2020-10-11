<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\Reader\PropertyInterface;

class SetMethodParametersGeneratorPlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<SET_METHOD_PARAMETERS>>';

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        $returnType = \sprintf(
            '?%s $%s',
            $property->getType(),
            $property->getName()
        );

        if ($this->propertyExplorer->isMixed($property->getType())) {
            $returnType = \sprintf(
                '$%s',
                $property->getName()
            );
        }

        return $returnType;
    }
}
