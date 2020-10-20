<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin\Schema;

use Popo\Plugin\Generator\AbstractGeneratorPlugin;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\Property;
use Popo\Schema\Reader\Schema;
use function var_export;

class CollectionItemsGeneratorPlugin extends AbstractGeneratorPlugin implements SchemaGeneratorPluginInterface
{
    const PATTERN = '<<COLLECTION_ITEMS>>';

    public function generate(Schema $schema): string
    {
        $items = [];

        foreach ($schema->getSchema() as $propertyData) {
            $property = $this->buildProperty($schema, $propertyData);
            $items[$property->getName()] = $property->getCollectionItem();
        }

        return var_export($items, true);
    }

    protected function buildProperty(Schema $schema, array $propertyData): Property
    {
        return new Property($schema, $propertyData);
    }
}
