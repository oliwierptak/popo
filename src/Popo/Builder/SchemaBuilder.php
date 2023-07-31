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
use Popo\Schema\Validator\Validator;

class SchemaBuilder
{
    protected SchemaLoader $loader;
    protected ConfigMerger $configMerger;
    private Validator $validator;

    public function __construct(SchemaLoader $loader, ConfigMerger $configMerger, Validator $validator)
    {
        $this->loader = $loader;
        $this->configMerger = $configMerger;
        $this->validator = $validator;
    }

    /**
     * @param \Popo\PopoConfigurator $configurator
     *
     * @return array<string, array<int|string, \Popo\Schema\Schema>>
     */
    public function build(PopoConfigurator $configurator): array
    {
        $result = [];
        $data = $this->loader->load($configurator);
        $sharedSchemaFile = $this->loader->loadSharedConfig($configurator->getSchemaConfigFilename());
        $tree = $this->generateSchemaTree($data, $sharedSchemaFile);

        foreach ($tree as $schemaName => $popoCollection) {
            foreach ($popoCollection as $popoName => $popoData) {
                $popoData = $this->validator->validate($popoData);
                
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
     * @return array<string, mixed>
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

    /**
     * @param \Popo\Schema\Schema $schema
     * @param array<string, array{name: string, type: string, comment: string|null, itemType: string|null, itemName: string|null, default: mixed, extra: mixed, attribute: string|null, attributes: array, mappingPolicy: array, mappingPolicyValue: string|null}> $propertyCollection
     *
     * @return \Popo\Schema\Schema
     */
    protected function buildSchemaPropertyCollection(Schema $schema, array $propertyCollection): Schema
    {
        $properties = [];
        foreach ($propertyCollection as $propertyName => $propertyData) {
            $properties[$propertyName] = $this->buildProperty($schema, $propertyData);
        }

        $schema->setPropertyCollection($properties);

        return $schema;
    }

    /**
     * @param \Popo\Schema\Schema $schema
     * @param array{name: string, type: string|null, comment: string|null, itemType: string|null, itemName: string|null, default: mixed, extra: mixed, attribute: string|null, attributes: array, mappingPolicy: array, mappingPolicyValue: string|null} $propertyData
     *
     * @return \Popo\Schema\Property\Property
     */
    private function buildProperty(Schema $schema, array $propertyData): Property
    {
        $property = (new Property)
            ->fromArray($propertyData);

        $default = $property->getDefault() ??
            $schema->getDefault()[$property->getName()] ??
            null;

        $property->setDefault($default);

        $mappingPolicyValues = [];
        foreach ($property->getMappingPolicy() as $mappingPolicyName) {
            $mappingPolicyValues[] = $mappingPolicyName;
        }

        $property->setMappingPolicy($mappingPolicyValues);

        return $property;
    }
}
