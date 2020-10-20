<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;
use function preg_replace_callback;
use function str_replace;
use function var_export;

class SchemaDataGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<SCHEMA_DATA>>';

    public function generate(Schema $schema): string
    {
        $defaults = [];
        $constants = [];

        foreach ($schema->getSchema() as $propertyData) {
            $property = $this->buildProperty($schema, $propertyData);

            if (!$property->hasDefault()) {
                continue;
            }

            $defaults[$property->getName()] = $property->getDefault();

            if ($property->isCollectionItem() || !$property->hasConstantValue()) {
                continue;
            }

            $constants[$property->getName()] = $property->getDefault();
        }

        $result = var_export($defaults, true);
        $result = (string)$this->fixConstantValuesEscaping($constants, $result);

        return $result;
    }

    protected function buildProperty(Schema $schema, array $propertyData): Property
    {
        return new Property($schema, $propertyData);
    }

    protected function fixConstantValuesEscaping(array $constants, ?string $result): ?string
    {
        if (empty($constants) || $result === null) {
            return $result;
        }

        foreach ($constants as $name => $defaultValue) {
            $pattern = "@('${name}') => '([^']*)',@i";

            $replacement = static function (array $matches) {
                $matches[2] = str_replace('\\\\', '\\', $matches[2]);

                return "${matches[1]} => ${matches[2]},";
            };

            $result = preg_replace_callback($pattern, $replacement, $result);
        }

        return $result;
    }
}
