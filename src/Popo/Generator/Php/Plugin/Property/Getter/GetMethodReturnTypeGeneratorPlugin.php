<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Getter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Schema;
use Popo\Schema\Reader\Property;

class GetMethodReturnTypeGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<GET_METHOD_RETURN_TYPE>>';

    public function generate(Schema $schema, Property $property): string
    {
        if ($this->propertyExplorer->isMixed($property->getType())) {
            return '';
        }

        $returnType = \sprintf(
            ': ?%s',
            $property->getType()
        );

        return $returnType;
    }
}
