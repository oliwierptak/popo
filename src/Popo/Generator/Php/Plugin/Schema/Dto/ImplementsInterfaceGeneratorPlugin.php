<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema\Dto;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\Schema;
use function sprintf;

class ImplementsInterfaceGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<IMPLEMENTS_INTERFACE>>';

    public function generate(Schema $schema): string
    {
        if ($schema->isAbstract()) {
            return '';
        }

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
