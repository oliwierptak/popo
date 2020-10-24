<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema\Popo;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\Schema;
use function sprintf;

class ReturnTypeGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<RETURN_TYPE>>';

    public function generate(Schema $schema): string
    {
        if ($schema->getReturnType() !== null) {
            return trim($schema->getReturnType());
        }

        $returnValue = $schema->getClassName();
        if ($schema->getParent() !== null) {
            if ($schema->getParent()->isAbstract()) {
                $returnValue = $schema->getParent()->getClassName();
            }
        }

        return sprintf(
            '%s',
            $returnValue
        );
    }
}
