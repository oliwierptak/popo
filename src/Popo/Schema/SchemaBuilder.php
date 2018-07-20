<?php

declare(strict_types = 1);

namespace Popo\Schema;

use Popo\Finder\FileLoaderInterface;
use Popo\Schema\Bundle\BundleSchemaFactoryInterface;
use Popo\Schema\Bundle\BundleSchemaInterface;
use Popo\Schema\Loader\JsonLoaderInterface;
use Popo\Schema\Reader\ReaderFactoryInterface;
use Popo\Schema\Reader\SchemaInterface;
use Symfony\Component\Finder\SplFileInfo;

class SchemaBuilder implements SchemaBuilderInterface
{
    /**
     * @var \Popo\Finder\FileLoaderInterface
     */
    protected $fileLoader;

    /**
     * @var \Popo\Schema\Loader\JsonLoaderInterface
     */
    protected $jsonLoader;

    /**
     * @var \Popo\Schema\Reader\ReaderFactoryInterface
     */
    protected $readerFactory;

    /**
     * @var \Popo\Schema\Bundle\BundleSchemaFactoryInterface
     */
    protected $bundleSchemaFactory;

    public function __construct(
        FileLoaderInterface $fileLoader,
        JsonLoaderInterface $jsonLoader,
        ReaderFactoryInterface $readerFactory,
        BundleSchemaFactoryInterface $bundleSchemaFactory
    ) {
        $this->fileLoader = $fileLoader;
        $this->jsonLoader = $jsonLoader;
        $this->readerFactory = $readerFactory;
        $this->bundleSchemaFactory = $bundleSchemaFactory;
    }

    /**
     * @param string $schemaDirectory
     * @param \Popo\Schema\SchemaConfiguratorInterface $configurator
     *
     * @return \Popo\Schema\Bundle\BundleSchemaInterface[]
     */
    public function build(string $schemaDirectory, SchemaConfiguratorInterface $configurator): array
    {
        $schemaFiles = $this->fileLoader->load(
            $schemaDirectory,
            $configurator->getSchemaPath(),
            $configurator->getSchemaFilename()
        );

        $fileInfoCollection = $this->groupBySchemaFile($schemaFiles);

        $data = [];
        foreach ($fileInfoCollection as $schemaFilename => $bundleSchemaFileCollection) {
            foreach ($bundleSchemaFileCollection as $bundleSchemaFile) {
                /** @var \Symfony\Component\Finder\SplFileInfo $bundleSchemaFile */
                $data[$schemaFilename][$bundleSchemaFile->getRelativePath()] = $this->buildBundleSchemaFiles(
                    $bundleSchemaFile,
                    $configurator
                );
            }
        }

        return $data;
    }

    /**
     * @param array $schemaFiles
     *
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    protected function groupBySchemaFile(array $schemaFiles): array
    {
        $fileInfoCollection = [];
        foreach ($schemaFiles as $fileInfo) {
            /** @var \Symfony\Component\Finder\SplFileInfo $fileInfo */
            $fileInfoCollection[$fileInfo->getFilename()][] = $fileInfo;
        }

        return $fileInfoCollection;
    }

    protected function markBundleSchema(
        BundleSchemaInterface $bundleSchema,
        SchemaConfiguratorInterface $configurator
    ): BundleSchemaInterface {
        $bundleNameFromFilename = $configurator->resolveBundleName(
            $bundleSchema->getSchemaFilename()->getFilename()
        );

        $bundleNameFromPath = $configurator->resolveBundleName(
            $bundleSchema->getSchemaFilename()->getRelativePath(),
            \DIRECTORY_SEPARATOR
        );

        $isBundleSchema = $this->isBundleSchema($bundleNameFromFilename, $bundleNameFromPath);
        $bundleSchema->setIsBundleSchema($isBundleSchema);

        return $bundleSchema;
    }

    protected function isBundleSchema(string $bundleNameFromFilename, string $bundleNameFromPath): bool
    {
        return \strcasecmp($bundleNameFromFilename, $bundleNameFromPath) === 0;
    }

    /**
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     *
     * @return \Popo\Schema\Reader\PropertyInterface[]
     */
    public function buildProperties(SchemaInterface $schema): array
    {
        $propertyCollection = $this->readerFactory->createPropertyCollection($schema);

        return $propertyCollection;
    }

    /**
     * @param \Popo\Schema\Bundle\BundleSchemaInterface $sourceBundleSchema
     * @param \Popo\Schema\Reader\PropertyInterface[] $propertyCollection
     *
     * @return \Popo\Schema\Bundle\BundleSchemaInterface
     */
    public function buildBundleSchemaWithProperties(
        BundleSchemaInterface $sourceBundleSchema,
        array $propertyCollection
    ): BundleSchemaInterface {
        $schemaData = [];
        foreach ($propertyCollection as $property) {
            $schemaData[] = $property->toArray();
        }

        $schema = $this->readerFactory->createSchema()
            ->setName($sourceBundleSchema->getSchema()->getName())
            ->setSchema($schemaData);

        $bundleSchema = $this->bundleSchemaFactory->createBundleSchema(
            $schema,
            $sourceBundleSchema->getSchemaFilename()
        );

        return $bundleSchema;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $bundleSchemaFile
     *
     * @return array
     */
    protected function loadSchemaData(SplFileInfo $bundleSchemaFile): array
    {
        $schemaData = $this->jsonLoader->load($bundleSchemaFile->getPathname());

        return $schemaData;
    }

    protected function buildBundleSchemaFiles(
        SplFileInfo $bundleSchemaFile,
        SchemaConfiguratorInterface $configurator
    ): array {
        /** @var \Symfony\Component\Finder\SplFileInfo $bundleSchemaFile */
        $schemaDataCollection = $this->loadSchemaData($bundleSchemaFile);

        $bundleSchemaFiles = [];
        foreach ($schemaDataCollection as $schemaData) {
            $schema = $this->buildSchema($schemaData);
            $bundleSchema = $this->buildBundleSchema($schema, $bundleSchemaFile, $configurator);

            $bundleSchemaFiles[$schema->getName()] = $bundleSchema;
        }

        return $bundleSchemaFiles;
    }

    protected function buildSchema(array $schemaData): SchemaInterface
    {
        $schema = $this->readerFactory->createSchema($schemaData);

        return $schema;
    }

    protected function buildBundleSchema(
        SchemaInterface $schema,
        SplFileInfo $bundleSchemaFile,
        SchemaConfiguratorInterface $configurator
    ): BundleSchemaInterface {
        $bundleSchema = $this->bundleSchemaFactory->createBundleSchema($schema, $bundleSchemaFile);
        $bundleSchema = $this->markBundleSchema($bundleSchema, $configurator);

        return $bundleSchema;
    }
}
