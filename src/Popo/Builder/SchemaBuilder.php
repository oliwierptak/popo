<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Loader\SchemaLoader;
use Popo\PopoConfigurator;
use Popo\PopoDefinesInterface;
use Popo\Schema\Config;
use Popo\Schema\Schema;
use Popo\Schema\Property;

class SchemaBuilder
{
    public function __construct(protected SchemaLoader $loader)
    {
    }

    public function build(PopoConfigurator $configurator): array
    {
        $data = $this->loader->load($configurator);

        $result = [];
        foreach ($data as $schemaData) {
            $defaultConfig = (new Config)->fromArray(
                $schemaData[PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG]
            );

            foreach ($schemaData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY] as $schemaName => $popoCollection) {
                foreach ($popoCollection as $popoName => $popoData) {
                    $popoConfig = $this->buildPopoConfig(
                        $defaultConfig,
                        $popoData[PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG] ?? []
                    );

                    $popoSchema = (new Schema)
                        ->setName($popoName)
                        ->setSchemaName($schemaName)
                        ->setConfig($popoConfig);

                    $result[$schemaName][$popoName] = $this->buildPropertyCollection($popoSchema, $popoData);
                }
            }
        }

        return $result;
    }

    protected function buildPopoConfig(Config $defaultConfig, array $popoData): Config
    {
        return (new Config)->fromArray(
            array_merge(
                $defaultConfig->toArray(),
                $popoData,
            )
        )->setDefaultConfig($defaultConfig);
    }

    protected function buildPropertyCollection(Schema $schema, array $schemaData): Schema
    {
        $propertyCollection = $schemaData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY] ?? [];
        $propertyDefaultConfig = $schemaData[PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT] ?? [];

        $propertyCollection = array_merge($propertyCollection, $schema->getConfig()->getPropertyCollection());
        $propertyCollection = $this->sortPropertyCollectionByName($propertyCollection);

        $properties = [];
        foreach ($propertyCollection as $propertyData) {
            $properties[] = $this->buildProperty($schema, $propertyData, $propertyDefaultConfig);
        }

        $schema->setPropertyCollection($properties);

        return $schema;
    }

    private function buildProperty(Schema $schema, array $propertyData, array $propertyDefaultConfig): Property
    {
        $property = (new Property)
            ->fromArray($propertyData);

        $default = $property->getDefault() ??
            $propertyDefaultConfig[$property->getName()] ??
            $schema->getConfig()->getDefault()[$property->getName()] ??
            null;

        $property->setDefault($default);

        return $property;
    }

    protected function sortPropertyCollectionByName(array $propertyCollection): array
    {
        usort(
            $propertyCollection,
            function (mixed $a, mixed $b) {
                return strcasecmp($a['name'], $b['name']);
            }
        );

        return $propertyCollection;
    }
}
