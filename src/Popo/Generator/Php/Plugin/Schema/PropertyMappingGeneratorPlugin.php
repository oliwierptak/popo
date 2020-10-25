<?php declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;
use function ctype_upper;
use function var_export;

class PropertyMappingGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<PROPERTY_MAPPING>>';

    public function generate(Schema $schema): string
    {
        $schemaKeys = [];

        foreach ($schema->getSchema() as $propertyData) {
            $property = $this->buildProperty($schema, $propertyData);
            $type = trim($property->getType());
            if ($this->isPopoClassName($type)) {
                $type = sprintf('\\%s\\%s', $schema->getNamespace(), $type);
            }
            $schemaKeys[$property->getName()] = $type;
        }

        return var_export($schemaKeys, true);
    }

    protected function buildProperty(Schema $schema, array $propertyData): Property
    {
        return new Property($schema, $propertyData);
    }

    protected function isPopoClassName(string $value): bool
    {
        return $value[0] !== '\\' && ctype_upper($value[0]);
    }
}
