<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter\Dto;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;
use function sprintf;

class SetMethodReturnTypeGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<SET_METHOD_RETURN_TYPE>>';

    public function generate(PropertyInterface $property): string
    {
        return sprintf(
            ': \%sInterface',
            $property->getSchema()->getName()
        );
    }
}
