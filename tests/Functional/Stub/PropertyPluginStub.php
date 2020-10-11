<?php

declare(strict_types = 1);

namespace Tests\Functional\Stub;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\Reader\PropertyInterface;

class PropertyPluginStub extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<CLASSNAME_STUB>>';

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        return $property->getSchema()->getClassName();
    }
}
