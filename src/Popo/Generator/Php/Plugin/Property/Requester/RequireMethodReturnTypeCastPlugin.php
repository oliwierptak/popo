<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Requester;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;

class RequireMethodReturnTypeCastPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<REQUIRE_METHOD_RETURN_TYPE_CAST>>';

    public function generate(Schema $schema, Property $property): string
    {
        $returnType = '';

        if ($this->propertyExplorer->hasTypeCast($property->getType())) {
            $returnType = '(' . $property->getType() . ')';
        }

        return $returnType;
    }
}
