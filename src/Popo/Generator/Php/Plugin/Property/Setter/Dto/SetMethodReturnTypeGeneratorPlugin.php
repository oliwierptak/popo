<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter\Dto;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;
use function sprintf;

class SetMethodReturnTypeGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<SET_METHOD_RETURN_TYPE>>';

    public function generate(Schema $schema, Property $property): string
    {
        $extends = trim((string) $schema->getExtends());
        if ($extends !== '') {
            return sprintf(
                ': %s',
                $extends
            );
        }

        if ($schema->isAbstract()) {
            return sprintf(
                ': %s',
                $property->getSchema()->getFullClassName()
            );
        }

        return sprintf(
            ': %sInterface',
            $property->getSchema()->getFullClassName()
        );
    }
}
