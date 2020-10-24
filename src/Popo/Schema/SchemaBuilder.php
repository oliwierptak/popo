<?php declare(strict_types = 1);

namespace Popo\Schema;

use Popo\Configurator;
use Popo\Finder\FileLoader;
use Popo\Schema\Bundle\BundleSchema;
use Popo\Schema\Bundle\BundleSchemaFactory;
use Popo\Schema\Loader\JsonLoader;
use Popo\Schema\Reader\ReaderFactory;
use Popo\Schema\Reader\Schema;
use Symfony\Component\Finder\SplFileInfo;
use function count;
use function explode;
use function strcasecmp;
use function trim;
use const DIRECTORY_SEPARATOR;

class SchemaBuilder
{
    protected FileLoader $fileLoader;

    protected JsonLoader $jsonLoader;

    protected ReaderFactory $readerFactory;

    protected BundleSchemaFactory $bundleSchemaFactory;

    public function __construct(
        FileLoader $fileLoader,
        JsonLoader $jsonLoader,
        ReaderFactory $readerFactory,
        BundleSchemaFactory $bundleSchemaFactory
    ) {
        $this->fileLoader = $fileLoader;
        $this->jsonLoader = $jsonLoader;
        $this->readerFactory = $readerFactory;
        $this->bundleSchemaFactory = $bundleSchemaFactory;
    }

    /**
     * @param \Popo\Configurator $configurator
     *
     * @return \Popo\Schema\Bundle\BundleSchema[]
     */
    public function build(Configurator $configurator): array
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

    /**
     * @param SplFileInfo $bundleSchemaFile
     * @param Configurator $configurator
     *
     * @return BundleSchema[][]
     */
    protected function buildBundleSchemaFiles(
        SplFileInfo $bundleSchemaFile,
        Configurator $configurator
    ): array {
        $schemaDataCollection = $this->loadSchemaData($bundleSchemaFile);

        $bundleSchemaFiles = [];

        foreach ($schemaDataCollection as $schemaData) {
            $schema = $this->buildSchema($schemaData, $configurator);
            $bundleSchema = $this->buildBundleSchema(
                $schema,
                $bundleSchemaFile,
                $configurator->getSchemaConfigurator()
            );
            $bundleSchemaFiles[$schema->getName()] = $bundleSchema;
        }

        return $bundleSchemaFiles;
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

    protected function buildSchema(array $schemaData, Configurator $configurator): Schema
    {
        $schema = $this->readerFactory->createSchema($schemaData);

        $schema = $this->updateAbstractFlag($schema, $configurator);
        $schema = $this->updateExtendsFlag($schema, $configurator);
        $schema = $this->updateReturnFlag($schema, $configurator);
        $schema = $this->updateWithInterfaceFlag($schema, $configurator);
        $schema = $this->updateWithPopoFlag($schema, $configurator);

        return $schema;
    }

    protected function updateAbstractFlag(Schema $schema, Configurator $configurator): Schema
    {
        $isAbstract = $schema->isAbstract();
        if ($configurator->getIsAbstract() === true) {
            $isAbstract = true;
        }
        $schema->setIsAbstract($isAbstract);

        if ($schema->isAbstract()) {
            $namespaceTokens = explode('\\', $schema->getName());

            if (count($namespaceTokens) > 1) {
                $schema->setName($schema->getNamespaceName() . '\\' . 'Abstract' . $schema->getClassName());
            }
            else {
                $schema->setName('Abstract' . $schema->getName());
            }
        }

        return $schema;
    }

    protected function updateExtendsFlag(Schema $schema, Configurator $configurator): Schema
    {
        $extends = $configurator->getExtends();
        if ($extends === null) {
            $extends = trim((string) $schema->getExtends());
        }
        $schema->setExtends($extends);

        return $schema;
    }

    protected function updateReturnFlag(Schema $schema, Configurator $configurator): Schema
    {
        if ($configurator->getReturnType() !== null) {
            $schema->setReturnType($configurator->getReturnType());
        }

        return $schema;
    }

    protected function updateWithInterfaceFlag(Schema $schema, Configurator $configurator): Schema
    {
        if ($configurator->getWithInterface() !== null) {
            $schema->setIsWithInterface($configurator->getWithInterface());
        }

        return $schema;
    }

    protected function updateWithPopoFlag(Schema $schema, Configurator $configurator)
    {
        if ($configurator->getWithPopo() !== null) {
            $schema->setIsWithPopo($configurator->getWithPopo());
        }

        return $schema;
    }

    protected function buildBundleSchema(
        Schema $schema,
        SplFileInfo $bundleSchemaFile,
        SchemaConfigurator $configurator
    ): BundleSchema {
        $bundleSchema = $this->bundleSchemaFactory->createBundleSchema($schema, $bundleSchemaFile);
        $bundleSchema = $this->markBundleSchema($bundleSchema, $configurator);

        return $bundleSchema;
    }

    protected function markBundleSchema(
        BundleSchema $bundleSchema,
        SchemaConfigurator $configurator
    ): BundleSchema {
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
     * @param \Popo\Schema\Reader\Schema $schema
     *
     * @return \Popo\Schema\Reader\Property[]
     */
    public function buildProperties(Schema $schema): array
    {
        return $this->readerFactory->createPropertyCollection($schema);
    }

    /**
     * @param \Popo\Schema\Bundle\BundleSchema $sourceBundleSchema
     * @param \Popo\Schema\Reader\Property[] $propertyCollection
     *
     * @return \Popo\Schema\Bundle\BundleSchema
     */
    public function buildBundleSchemaWithProperties(
        BundleSchema $sourceBundleSchema,
        array $propertyCollection
    ): BundleSchema {
        $properties = [];
        $schemaData = $sourceBundleSchema->getSchema()->toArray();
        foreach ($propertyCollection as $property) {
            $properties[] = $property->toArray();
        }
        $schemaData['schema'] = $properties;

        $schema = $this->readerFactory->createSchema($schemaData);
        $bundleSchema = $this->bundleSchemaFactory->createBundleSchema(
            $schema,
            $sourceBundleSchema->getSchemaFilename()
        );

        $bundleSchema->setIsBundleSchema($sourceBundleSchema->isBundleSchema());

        return $bundleSchema;
    }
}
