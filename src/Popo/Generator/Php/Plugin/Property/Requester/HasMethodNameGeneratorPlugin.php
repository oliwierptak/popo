<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Requester;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;

class HasMethodNameGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<HAS_METHOD_NAME>>';

    public function generate(PropertyInterface $property): string
    {
        $name = $property->getName();

        return 'has' . \ucfirst($name);
    }
}
