<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Property;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;

class PropertyNameGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<PROPERTY_NAME>>';

    public function generate(Schema $schema, Property $property): string
    {
        return $property->getName();
    }
}
