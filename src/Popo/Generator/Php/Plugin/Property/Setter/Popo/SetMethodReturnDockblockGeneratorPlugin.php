<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter\Popo;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;
use function sprintf;
use function trim;

class SetMethodReturnDockblockGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<SET_METHOD_RETURN_DOCKBLOCK>>';

    public function generate(Schema $schema, Property $property): string
    {
        $generated = 'self';
        $docblock = trim($property->getDocblock());
        if ($docblock !== '') {
            $docblock = ' ' . $docblock;
        }

        $extends = trim((string) $schema->getExtends());
        if ($extends !== '') {
            $generated = sprintf(
                '%s',
                $extends
            );
        }

        if ($schema->isAbstract()) {
            $generated = sprintf(
                '\%s',
                $property->getSchema()->getName()
            );
        }

        $string = sprintf(
            '%s%s',
            $generated,
            $docblock
        );

        return $string;
    }
}
