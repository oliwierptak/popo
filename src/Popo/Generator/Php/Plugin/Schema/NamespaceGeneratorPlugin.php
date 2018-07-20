<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;

class NamespaceGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<NAMESPACE>>';

    public function generate(SchemaInterface $schema): string
    {
        return $schema->getNamespaceName();
    }
}
