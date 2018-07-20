<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyInterface;

class SetMethodNameGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<SET_METHOD_NAME>>';

    public function generate(PropertyInterface $property): string
    {
        $name = \ucfirst($property->getName());

        return 'set' . $name;
    }
}
