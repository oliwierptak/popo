<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\Schema;
use function trim;

class FromArrayResultPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<FROM_ARRAY_RESULT>>';

    public function generate(Schema $schema): string
    {
        $extends = trim((string) $schema->getExtends());
        $result = '[]';

        if ($extends !== '') {
            $result = 'parent::fromArray($data)';
        }

        return $result;
    }
}
