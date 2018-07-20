<?php

declare(strict_types = 1);

namespace Popo\Schema;

use Popo\Schema\Bundle\BundleSchemaInterface;
use Popo\Schema\Reader\SchemaInterface;

interface SchemaBuilderInterface
{
    /**
     * @param string $schemaDirectory
     * @param \Popo\Schema\SchemaConfiguratorInterface $configurator
     *
     * @return \Popo\Schema\Bundle\BundleSchemaInterface[]
     */
    public function build(string $schemaDirectory, SchemaConfiguratorInterface $configurator): array;

    /**
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     *
     * @return \Popo\Schema\Reader\PropertyInterface[]
     */
    public function buildProperties(SchemaInterface $schema): array;

    /**
     * @param \Popo\Schema\Bundle\BundleSchemaInterface $sourceBundleSchema
     * @param \Popo\Schema\Reader\PropertyInterface[] $propertyCollection
     *
     * @return \Popo\Schema\Bundle\BundleSchemaInterface
     */
    public function buildBundleSchemaWithProperties(
        BundleSchemaInterface $sourceBundleSchema,
        array $propertyCollection
    ): BundleSchemaInterface;
}
