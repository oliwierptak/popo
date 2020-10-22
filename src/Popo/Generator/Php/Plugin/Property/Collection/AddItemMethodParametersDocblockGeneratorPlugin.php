<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Collection;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Schema;
use Popo\Schema\Reader\Property;
use function sprintf;
use function trim;

class AddItemMethodParametersDocblockGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<ADD_ITEM_METHOD_PARAM_DOCKBLOCK>>';

    public function generate(Schema $schema, Property $property): string
    {
        $docblock = trim($property->getDocblock());
        if ($docblock !== '') {
            $docblock = ' ' . $docblock;
        }

        $name = '$item';

        if ($property->isCollectionItem()) {
            $name = ' ' . $name;
        }

        $interfacePostfix = '';
        if ($property->getSchema()->isWithInterface()) {
            $interfacePostfix = 'Interface';
        }

        $string = sprintf(
            '%s%s%s%s',
            $docblock,
            $property->getCollectionItem(),
            $interfacePostfix,
            $name
        );

        return $string;
    }
}
