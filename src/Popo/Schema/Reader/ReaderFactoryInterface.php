<?php

declare(strict_types = 1);

namespace Popo\Schema\Reader;

interface ReaderFactoryInterface
{
    public function createProperty(SchemaInterface $schema, array $propertySchema): PropertyInterface;

    public function createSchema(array $schema = []): SchemaInterface;

    /**
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     *
     * @return \Popo\Schema\Reader\PropertyInterface[]
     */
    public function createPropertyCollection(SchemaInterface $schema): array;

    public function createPropertyExplorer(): PropertyExplorerInterface;
}
