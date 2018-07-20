<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema\Popo;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;

class ReturnTypeGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<RETURN_TYPE>>';

    public function generate(SchemaInterface $schema): string
    {
        $returnType = \sprintf(
            '\%s',
            $schema->getName()
        );

        return $returnType;
    }
}
