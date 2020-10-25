<?php declare(strict_types = 1);

namespace Popo\Schema\Reader;

class ReaderFactory
{
    public function createSchema(array $schema = []): Schema
    {
        return new Schema($schema);
    }

    /**
     * @param \Popo\Schema\Reader\Schema $schema
     *
     * @return \Popo\Schema\Reader\Property[]
     */
    public function createPropertyCollection(Schema $schema): array
    {
        $propertyCollection = [];

        foreach ($schema->getSchema() as $propertyData) {
            $propertyCollection[] = $this->createProperty($schema, $propertyData);
        }

        return $propertyCollection;
    }

    public function createProperty(Schema $schema, array $propertySchema): Property
    {
        return new Property($schema, $propertySchema);
    }

    public function createPropertyExplorer(): PropertyExplorer
    {
        return new PropertyExplorer();
    }
}
