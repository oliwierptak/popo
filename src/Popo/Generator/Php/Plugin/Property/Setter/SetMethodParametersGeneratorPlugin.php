<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;

class SetMethodParametersGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<SET_METHOD_PARAMETERS>>';

    public function generate(Schema $schema, Property $property): string
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
