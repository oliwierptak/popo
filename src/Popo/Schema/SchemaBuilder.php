<?php

declare(strict_types = 1);

namespace Popo\Schema;

use Popo\Builder\BuilderConfigurator;
use Popo\Finder\FileLoaderInterface;
use Popo\Schema\Bundle\BundleSchemaFactoryInterface;
use Popo\Schema\Bundle\BundleSchemaInterface;
use Popo\Schema\Loader\JsonLoaderInterface;
use Popo\Schema\Reader\ReaderFactoryInterface;
use Popo\Schema\Reader\SchemaInterface;
use Symfony\Component\Finder\SplFileInfo;
use function count;
use function explode;
use function strcasecmp;
use function trim;
use const DIRECTORY_SEPARATOR;

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
     * @param \Popo\Builder\BuilderConfigurator $configurator
     *
     * @return \Popo\Schema\Bundle\BundleSchemaInterface[]
     */
    public function build(BuilderConfigurator $configurator): array
    {
        $schemaFiles = $this->fileLoader->load(
            $configurator->getSchemaDirectory(),
            $configurator->getSchemaConfigurator()->getSchemaPath(),
            $configurator->getSchemaConfigurator()->getSchemaFilename()
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
            DIRECTORY_SEPARATOR
        );

        $isBundleSchema = $this->isBundleSchema($bundleNameFromFilename, $bundleNameFromPath);
        $bundleSchema->setIsBundleSchema($isBundleSchema);

        return $bundleSchema;
    }

    protected function isBundleSchema(string $bundleNameFromFilename, string $bundleNameFromPath): bool
    {
        return strcasecmp($bundleNameFromFilename, $bundleNameFromPath) === 0;
    }

    /**
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     *
     * @return \Popo\Schema\Reader\PropertyInterface[]
     */
    public function buildProperties(SchemaInterface $schema): array
    {
        return $this->readerFactory->createPropertyCollection($schema);
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

        $schema = $this->readerFactory->createSchema(
            $sourceBundleSchema->getSchema()->toArray()
        );

        return $this->bundleSchemaFactory->createBundleSchema(
            $schema,
            $sourceBundleSchema->getSchemaFilename()
        );
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $bundleSchemaFile
     *
     * @return array
     */
    protected function loadSchemaData(SplFileInfo $bundleSchemaFile): array
    {
        return $this->jsonLoader->load($bundleSchemaFile->getPathname());
    }

    protected function buildBundleSchemaFiles(
        SplFileInfo $bundleSchemaFile,
        BuilderConfigurator $configurator
    ): array {
        $schemaDataCollection = $this->loadSchemaData($bundleSchemaFile);

        $bundleSchemaFiles = [];

        foreach ($schemaDataCollection as $schemaData) {
            $schema = $this->buildSchema($schemaData, $configurator);
            $bundleSchema = $this->buildBundleSchema($schema, $bundleSchemaFile, $configurator->getSchemaConfigurator());
            $bundleSchemaFiles[$schema->getName()] = $bundleSchema;
        }

        return $bundleSchemaFiles;
    }

    protected function buildSchema(array $schemaData, BuilderConfigurator $configurator): SchemaInterface
    {
        $schema = $this->readerFactory->createSchema($schemaData);
        $isAbstract = $configurator->getIsAbstract();
        $extends = $configurator->getExtends();

        if ($isAbstract === null) {
            $isAbstract = $schema->isAbstract();
        }

        $schema->setIsAbstract($isAbstract);

        if ($extends === null) {
            $extends = trim((string)$schema->getExtends());
        }

        $schema->setExtends($extends);

        if ($schema->isAbstract()) {
            $namespaceTokens = explode('\\', $schema->getName());

            if (count($namespaceTokens) > 1) {
                $schema->setName($schema->getNamespaceName() . '\\' . 'Abstract' . $schema->getClassName());
            } else {
                $schema->setName('Abstract' . $schema->getName());
            }
        }

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
