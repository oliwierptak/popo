<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter\Popo;

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
        return sprintf(
            ': \%s',
            $property->getSchema()->getName()
        );
    }
}
