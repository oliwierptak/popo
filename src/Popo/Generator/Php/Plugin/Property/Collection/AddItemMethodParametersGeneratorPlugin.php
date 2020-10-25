<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Collection;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;

class AddItemMethodParametersGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<ADD_ITEM_METHOD_PARAMETERS>>';

    public function generate(Schema $schema, Property $property): string
    {
        $string = \sprintf(
            '$%s',
            'item'
        );

        $interfacePostfix = '';
        if ($property->getSchema()->isWithInterface()) {
            $interfacePostfix = 'Interface';
        }

        if ($property->isCollectionItem()) {
            $string = \sprintf(
                '%s%s $%s',
                $property->getCollectionItem(),
                $interfacePostfix,
                'item'
            );
        }

        return $string;
    }
}
