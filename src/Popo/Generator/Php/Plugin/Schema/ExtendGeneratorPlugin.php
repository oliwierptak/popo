<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use function sprintf;
use function trim;

class ExtendGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<EXTENDS>>';

    public function generate(SchemaInterface $schema): string
    {
        $extends = trim((string)$schema->getExtends());

        if ($extends !== '') {
            $extends = sprintf(' extends %s', $extends);
        }

        return $extends;
    }
}
