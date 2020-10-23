<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property\Getter;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;

class GetMethodNameGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<GET_METHOD_NAME>>';

    public function generate(Schema $schema, Property $property): string
    {
        $name = $property->getName();

        if ($this->propertyExplorer->isBoolean($property->getType())) {
            return $name;
        }

        return 'get' . \ucfirst($name);
    }
}
