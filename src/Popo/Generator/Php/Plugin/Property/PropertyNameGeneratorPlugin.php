<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;

class PropertyNameGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<PROPERTY_NAME>>';

    public function generate(PropertyInterface $property): string
    {
        return $property->getName();
    }
}
