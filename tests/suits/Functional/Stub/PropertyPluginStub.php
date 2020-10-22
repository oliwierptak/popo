<?php

declare(strict_types = 1);

namespace TestsSuites\Popo\Functional\Stub;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Schema;
use Popo\Schema\Reader\Property;

class PropertyPluginStub extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<CLASSNAME_STUB>>';

    public function generate(Schema $schema, Property $property): string
    {
        return $property->getSchema()->getClassName();
    }
}
