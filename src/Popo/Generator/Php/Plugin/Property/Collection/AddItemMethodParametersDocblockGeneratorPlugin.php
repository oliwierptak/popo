<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Collection;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;

class AddItemMethodParametersDocblockGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<ADD_ITEM_METHOD_PARAM_DOCKBLOCK>>';

    public function generate(PropertyInterface $property): string
    {
        $docblock = \trim($property->getDocblock());
        if ($docblock !== '') {
            $docblock = ' ' . $docblock;
        }

        $name = '$' . $property->getName() . 'Item';
        if ($property->hasCollectionItem()) {
            $name = ' ' . $name;
        }

        $string = \sprintf(
            '%s%s%s',
            $docblock,
            $property->getCollectionItem(),
            $name
        );

        return $string;
    }
}
