<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema\Popo;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\Schema;

class ImplementsInterfaceGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<IMPLEMENTS_INTERFACE>>';

    public function generate(Schema $schema): string
    {
        if (!$schema->isWithInterface()) {
            return '';
        }

        return sprintf(
            'implements \%s\%sInterface',
            $schema->getNamespaceWithInterface() ?? $schema->getNamespaceName(),
            $schema->getClassName()
        );
    }
}
