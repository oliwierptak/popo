<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Loader\SchemaLoader;
use Popo\PopoConfigurator;
use Popo\PopoDefinesInterface;
use Popo\Schema\Config\Config;
use Popo\Schema\Config\ConfigMerger;
use Popo\Schema\File\SchemaFile;
use Popo\Schema\Property\Property;
use Popo\Schema\Schema;

class SchemaBuilder
{
    protected SchemaLoader $loader;
    protected ConfigMerger $configMerger;

    public function __construct(SchemaLoader $loader, ConfigMerger $configMerger)
    {
        $this->loader = $loader;
        $this->configMerger = $configMerger;
    }

    public function build(PopoConfigurator $configurator): array
    {
        $result = [];
        $data = $this->loader->load($configurator);
        $sharedSchemaFile = $this->loader->loadSharedConfig($configurator->getSchemaConfigFilename());
        $tree = $this->generateSchemaTree($data, $sharedSchemaFile);

        foreach ($tree as $schemaName => $popoCollection) {
            foreach ($popoCollection as $popoName => $popoData) {
                $popoSchema = (new Schema)
                    ->setName($popoName)
                    ->setSchemaName($schemaName)
                    ->setDefault($popoData[PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT] ?? [])
                    ->setConfig(
                        (new Config)->fromArray($popoData[PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG])
                    );

                $popoSchema = $this->updateSchemaConfigFromCommandConfiguration(
                    $popoSchema,
                    $configurator
                );

                $result[$schemaName][$popoName] = $this->buildSchemaPropertyCollection(
                    $popoSchema,
                    $popoData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY],
                );
            }
        }

        return $result;
    }

    /**
     * @param \Popo\Schema\File\SchemaFile[] $data
     * @param \Popo\Schema\File\SchemaFile $sharedSchemaFile
     *
     * @return array
     */
    protected function generateSchemaTree(array $data, SchemaFile $sharedSchemaFile): array
    {
        $result = [];

        foreach ($data as $schemaFile) {
            $schemaCollection = array_merge($sharedSchemaFile->getData(), $schemaFile->getData());

            foreach ($schemaCollection as $schemaName => $popoCollection) {
                $schemaConfigData = $this->configMerger->mergeSchemaConfiguration(
                    $schemaName,
                    $sharedSchemaFile,
                    $schemaFile
                );

                $result = $this->configMerger->mergePopoCollection(
                    $schemaName,
                    $popoCollection,
                    $schemaConfigData,
                    $result
                );
            }
        }

        return $result;
    }

    public function updateSchemaConfigFromCommandConfiguration(
        Schema $popoSchema,
        PopoConfigurator $configurator
    ): Schema {
        $popoSchema->getConfig()->setNamespace(
            $configurator->getNamespace() ?? $popoSchema->getConfig()->getNamespace()
        );
        $popoSchema->getConfig()->setNamespaceRoot(
            $configurator->getNamespaceRoot() ?? $popoSchema->getConfig()->getNamespaceRoot()
        );
        $popoSchema->getConfig()->setOutputPath(
            $configurator->getOutputPath() ?? $popoSchema->getConfig()->getOutputPath()
        );

        return $popoSchema;
    }

    protected function buildSchemaPropertyCollection(Schema $schema, array $propertyCollection): Schema
    {
        $properties = [];
        foreach ($propertyCollection as $propertyName => $propertyData) {
            $properties[$propertyName] = $this->buildProperty($schema, $propertyData);
        }

        $schema->setPropertyCollection($properties);

        return $schema;
    }

    private function buildProperty(Schema $schema, array $propertyData): Property
    {
        $property = (new Property)
            ->fromArray($propertyData);

        $default = $property->getDefault() ??
            $schema->getDefault()[$property->getName()] ??
            null;

        $property->setDefault($default);

        return $property;
    }
}
