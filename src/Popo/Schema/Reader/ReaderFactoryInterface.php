<?php

declare(strict_types = 1);

namespace Popo\Schema\Reader;

interface ReaderFactoryInterface
{
    /**
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     * @param array $propertySchema
     *
     * @return \Popo\Schema\Reader\PropertyInterface
     */
    public function createProperty(SchemaInterface $schema, array $propertySchema): PropertyInterface;

    /**
     * @param array $schema
     *
     * @return \Popo\Schema\Reader\SchemaInterface
     */
    public function createSchema(array $schema = []): SchemaInterface;

    /**
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     *
     * @return \Popo\Schema\Reader\PropertyInterface[]
     */
    public function createPropertyCollection(SchemaInterface $schema): array;
}
