<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Generator\GeneratorInterface;
use Popo\Schema\SchemaBuilderInterface;
use Popo\Schema\SchemaMergerInterface;
use Popo\Writer\WriterFactoryInterface;

class BuilderWriter implements BuilderWriterInterface
{
    /**
     * @var \Popo\Schema\SchemaBuilderInterface
     */
    protected $schemaBuilder;

    /**
     * @var \Popo\Schema\SchemaMergerInterface
     */
    protected $schemaMerger;

    /**
     * @var \Popo\Writer\WriterFactoryInterface
     */
    protected $writerFactory;

    public function __construct(SchemaBuilderInterface $schemaBuilder, SchemaMergerInterface $schemaMerger, WriterFactoryInterface $writerFactory)
    {
        $this->schemaBuilder = $schemaBuilder;
        $this->schemaMerger = $schemaMerger;
        $this->writerFactory = $writerFactory;
    }

    public function write(BuilderConfigurator $configurator, GeneratorInterface $generator): int
    {
        $schemaFiles = $this->schemaBuilder->build($configurator);
        $mergedSchemaFiles = $this->schemaMerger->merge($schemaFiles);
        $bundleWriter = $this->writerFactory->createBundleProjectWriter($generator, $configurator->getNamespace());

        $numberOfFilesGenerated = $bundleWriter->write(
            $mergedSchemaFiles, $configurator->getExtension(), $configurator->getOutputDirectory(), false
        );

        if ($configurator->getWithInterface()) {
            $numberOfFilesGenerated += $bundleWriter->write(
                $mergedSchemaFiles, $configurator->getExtension(), $configurator->getOutputDirectory(), true
            );
        }

        return $numberOfFilesGenerated;
    }
}
