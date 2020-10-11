<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Collection;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\Reader\PropertyInterface;
use function trim;
use function ucfirst;

class AddItemMethodNamePlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<ADD_ITEM_METHOD_NAME>>';

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        $name = $property->getName();

        $singular = trim($property->getSingular());

        if ($singular !== '') {
            $name = $singular;
        } else {
            $name .= 'Item';
        }

        return 'add' . ucfirst($name);
    }
}
