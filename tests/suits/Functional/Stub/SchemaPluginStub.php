<?php

declare(strict_types = 1);

namespace TestsSuites\Popo\Functional\Stub;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\Schema;

class SchemaPluginStub extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<CLASSNAME_STUB>>';

    public function generate(Schema $schema): string
    {
        return $schema->getClassName();
    }
}
