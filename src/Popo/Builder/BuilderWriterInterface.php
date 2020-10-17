<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Generator\GeneratorInterface;

interface BuilderWriterInterface
{
    /**
     * Specification:
     * - Creates collection of schema files using SchemaBuilder
     * - Merges schema files using SchemaMerger
     * - Creates BundleWriter using WriterFactory
     * - Writes merged schema files to specified output directory
     * - Return number of written files
     *
     * @param BuilderConfigurator $configurator
     * @param GeneratorInterface $generator
     * @param \Popo\Schema\Bundle\BundleSchemaInterface[] $schemaFiles
     *
     * @return int
     */
    public function write(BuilderConfigurator $configurator, GeneratorInterface $generator, array $schemaFiles): int;
}
