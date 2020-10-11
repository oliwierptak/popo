<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Collection;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\Reader\PropertyInterface;

class AddItemMethodParametersGeneratorPlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<ADD_ITEM_METHOD_PARAMETERS>>';

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        $string = \sprintf(
            '$%s',
            'item'
        );

        if ($property->isCollectionItem()) {
            $string = \sprintf(
                '%s $%s',
                $property->getCollectionItem(),
                'item'
            );
        }

        return $string;
    }
}
