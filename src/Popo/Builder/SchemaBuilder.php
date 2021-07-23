<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Loader\SchemaLoader;
use Popo\PopoConfigurator;
use Popo\Schema\Config;
use Popo\Schema\Schema;
use Popo\Schema\Property;
use Popo\Schema\SchemaFile;
use function array_merge;

class SchemaBuilder
{
    public function __construct(protected SchemaLoader $loader)
    {
    }

    public function build(PopoConfigurator $configurator): array
    {
        $result = [];
        $data = $this->loader->load($configurator);
        $sharedConfig = $this->generateSharedConfig($configurator, $data);

        dump($sharedConfig);

        foreach ($data as $schemaFile) {
            $defaultPopoSettings = $schemaFile->getData()['config'] ?? [];
            $defaultPopoDefaults = $schemaFile->getData()['default'] ?? [];

            foreach ($schemaFile->getData()['property'] as $schemaName => $popoCollection) {
                $defaultPopoSettings = array_merge(
                    $sharedConfig['config'] ?? [],
                    $sharedConfig['sharedSchemaConfig'][$schemaName]['config'] ?? [],
                    $defaultPopoSettings,
                );
                $defaultPopoDefaults = array_merge(
                    $sharedConfig['config']['default'] ?? [],
                    $sharedConfig['sharedSchemaConfig'][$schemaName]['config']['default'] ?? [],
                    $defaultPopoDefaults,
                );
                unset($defaultPopoSettings['default']);
                unset($defaultPopoSettings['property']);

                $defaultPopoProperties = array_merge(
                    $sharedConfig['property'] ?? [],
                    $sharedConfig['sharedSchemaConfig'][$schemaName]['config']['property'] ?? [],
                );

                foreach ($popoCollection as $popoName => $popoData) {
                    $popoData['config'] = array_merge($defaultPopoSettings, $popoData['config'] ?? []);
                    $popoData['default'] = array_merge($defaultPopoDefaults, $popoData['default'] ?? []);
                    $popoData['property'] = array_merge($defaultPopoProperties, $popoData['property'] ?? []);

                    unset($popoData['config']['default']);
                    unset($popoData['config']['property']);

                    $popoSchema = (new Schema)
                        ->setName($popoName)
                        ->setSchemaName($schemaName)
                        ->setConfig((new Config)->fromArray($popoData['config']))
                        ->setDefault($popoData['default'] ?? []);

                    $result[$schemaName][$popoName] = $this->buildPropertyCollection($popoSchema, $popoData['property']);
                }
            }
        }

        return $result;
    }

    protected function generateSharedConfig(PopoConfigurator $configurator, array $data): array
    {
        $schemaConfigFilename = trim((string) $configurator->getSchemaConfigFilename());
        if ($schemaConfigFilename === '') {
            return [];
        }

        $sharedConfig = current(
            $this->loader->load(
                (new PopoConfigurator)->setSchemaPath($configurator->getSchemaConfigFilename())
            )
        );
        if (($sharedConfig instanceof SchemaFile) === false) {
            return [];
        }

        $sharedConfigData = [];
        foreach ($data as $schemaFile) {
            $sharedConfigData = array_merge($sharedConfig->getSharedConfig(), $schemaFile->getSharedConfig());
        }

        $configData = $sharedConfig->getData()['config'] ?? [];
        $configProperty = $configData['property'] ?? [];
        unset($configData['property']);

        return [
            'config' => $configData,
            'property' => $configProperty,
            'sharedSchemaConfig' => $sharedConfigData,
        ];
    }

    protected function buildPropertyCollection(Schema $schema, array $propertyCollection): Schema
    {
        $propertyCollection = $this->sortPropertyCollectionByName($propertyCollection);

        $properties = [];
        foreach ($propertyCollection as $propertyData) {
            $properties[] = $this->buildProperty($schema, $propertyData, $schema->getDefault());
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
            $schema->getDefault()[$property->getName()] ??
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
