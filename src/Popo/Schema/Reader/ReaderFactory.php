<?php

declare(strict_types = 1);

namespace Popo\Schema\Reader;

class ReaderFactory implements ReaderFactoryInterface
{
    /**
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     * @param array $propertySchema
     *
     * @return \Popo\Schema\Reader\PropertyInterface
     */
    public function createProperty(SchemaInterface $schema, array $propertySchema): PropertyInterface
    {
        return new Property($schema, $propertySchema);
    }

    /**
     * @param array $schema
     *
     * @return \Popo\Schema\Reader\SchemaInterface
     */
    public function createSchema(array $schema = []): SchemaInterface
    {
        return new Schema($schema);
    }

    /**
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     *
     * @return \Popo\Schema\Reader\PropertyInterface[]
     */
    public function createPropertyCollection(SchemaInterface $schema): array
    {
        $propertyCollection = [];

        foreach ($schema->getSchema() as $propertyData) {
            $propertyCollection[] = $this->createProperty($schema, $propertyData);
        }

        return $propertyCollection;
    }
}
