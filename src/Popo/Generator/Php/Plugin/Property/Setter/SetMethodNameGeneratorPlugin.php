<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Setter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Schema\Reader\SchemaInterface;
use Popo\Schema\Reader\PropertyInterface;

class SetMethodNameGeneratorPlugin extends AbstractGeneratorPlugin implements GeneratorPluginInterface
{
    const PATTERN = '<<SET_METHOD_NAME>>';

    public function generate(SchemaInterface $schema, PropertyInterface $property): string
    {
        $name = \ucfirst($property->getName());

        return 'set' . $name;
    }
}
