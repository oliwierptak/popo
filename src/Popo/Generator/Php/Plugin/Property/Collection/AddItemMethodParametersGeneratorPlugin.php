<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Collection;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;

class AddItemMethodParametersGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<ADD_ITEM_METHOD_PARAMETERS>>';

    public function generate(PropertyInterface $property): string
    {
        $string = \sprintf(
            '%s $%s',
            $property->getType(),
            $property->getName()
        );

        if ($property->isCollectionItem()) {
            $string = \sprintf(
                '%s $%sItem',
                $property->getCollectionItem(),
                $property->getName()
            );
        }

        if ($this->propertyExplorer->isMixed($property->getType())) {
            $string = \sprintf(
                '$%s',
                $property->getName()
            );
        }

        return $string;
    }
}
