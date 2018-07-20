<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter\Popo;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;

class SetMethodReturnTypeGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<SET_METHOD_RETURN_TYPE>>';

    public function generate(PropertyInterface $property): string
    {
        $returnType = \sprintf(
            ': \%s',
            $property->getSchema()->getName()
        );

        return $returnType;
    }
}
