<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter\Dto;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\Reader\PropertyInterface;
use function sprintf;

class SetMethodReturnTypeGeneratorPlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<SET_METHOD_RETURN_TYPE>>';

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        $extends = trim((string)$schema->getExtends());
        if ($extends !== '') {
            return sprintf(
                ': %s',
                $extends
            );
        }

        if ($schema->isAbstract()) {
            return sprintf(
                ': \%s',
                $property->getSchema()->getName()
            );
        }

        return sprintf(
            ': \%sInterface',
            $property->getSchema()->getName()
        );
    }
}
