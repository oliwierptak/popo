<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema\Popo;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;

class ImplementsInterfaceGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<IMPLEMENTS_INTERFACE>>';

    public function generate(SchemaInterface $schema): string
    {
        return '';
    }
}
