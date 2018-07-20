<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;

class ClassNameGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<CLASSNAME>>';

    public function generate(SchemaInterface $schema): string
    {
        return $schema->getClassName();
    }
}
