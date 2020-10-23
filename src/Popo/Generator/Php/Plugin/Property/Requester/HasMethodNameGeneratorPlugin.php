<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Requester;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;

class HasMethodNameGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<HAS_METHOD_NAME>>';

    public function generate(Schema $schema, Property $property): string
    {
        $name = $property->getName();

        return 'has' . \ucfirst($name);
    }
}
