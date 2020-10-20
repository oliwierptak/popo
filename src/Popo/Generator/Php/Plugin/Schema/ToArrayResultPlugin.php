<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\Schema;
use function trim;

class ToArrayResultPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<TO_ARRAY_RESULT>>';

    public function generate(Schema $schema): string
    {
        $extends = trim((string)$schema->getExtends());
        $result = '[]';

        if ($extends !== '') {
            $result = 'parent::toArray()';
        }

        return $result;
    }
}
