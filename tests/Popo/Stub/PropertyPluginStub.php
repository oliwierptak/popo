<?php

declare(strict_types = 1);

namespace Tests\Popo\Stub;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;

class PropertyPluginStub extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<CLASSNAME_STUB>>';

    public function generate(PropertyInterface $property): string
    {
        return $property->getSchema()->getClassName();
    }
}
