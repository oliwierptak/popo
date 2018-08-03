<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Collection;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;

class AddItemMethodNamePlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<ADD_ITEM_METHOD_NAME>>';

    public function generate(PropertyInterface $property): string
    {
        $name = $property->getName();

        $singular = \trim($property->getSingular());
        if ($singular !== '') {
            $name = $singular;
        }

        return 'add' . \ucfirst($name) . 'Item';
    }
}
