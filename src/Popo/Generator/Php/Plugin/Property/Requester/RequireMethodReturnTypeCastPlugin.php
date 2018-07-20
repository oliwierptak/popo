<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Requester;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;

class RequireMethodReturnTypeCastPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<REQUIRE_METHOD_RETURN_TYPE_CAST>>';

    public function generate(PropertyInterface $property): string
    {
        $returnType = '';

        if ($this->propertyExplorer->hasTypeCast($property->getType())) {
            $returnType = '('.$property->getType().')';
        }

        return $returnType;
    }
}
