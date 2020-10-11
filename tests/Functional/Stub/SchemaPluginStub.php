<?php

declare(strict_types = 1);

namespace Tests\Functional\Stub;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;

class SchemaPluginStub extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<CLASSNAME_STUB>>';

    public function generate(SchemaInterface $schema): string
    {
        return $schema->getClassName();
    }
}
