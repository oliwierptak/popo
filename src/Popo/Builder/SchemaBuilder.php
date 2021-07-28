<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Loader\SchemaLoader;
use Popo\PopoConfigurator;
use Popo\PopoDefinesInterface;
use Popo\Schema\Config;
use Popo\Schema\ConfigMerger;
use Popo\Schema\Schema;
use Popo\Schema\Property;
use Popo\Schema\SchemaFile;
use RuntimeException;
use function array_key_exists;

class SchemaBuilder
{
    public function __construct(protected SchemaLoader $loader, protected ConfigMerger $configMerger)
    {
    }

    public function build(PopoConfigurator $configurator): array
    {
        $result = [];
        $data = $this->loader->load($configurator);
        $globalConfig = $this->generateSharedConfig($configurator, $data);

        foreach ($data as $schemaFile) {
            foreach ($schemaFile->getData() as $schemaName => $popoCollection) {
                $schemaConfigData = $this->configMerger->mergeGlobalSchema($globalConfig, $schemaName);

                foreach ($popoCollection as $popoName => $popoData) {
                    $popoData = $this->configMerger->mergePopoSchema($schemaFile, $schemaConfigData, $popoData);

                    $popoSchema = (new Schema)
                        ->setName($popoName)
                        ->setSchemaName($schemaName)
                        ->setDefault($popoData[PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT] ?? [])
                        ->setConfig(
                            (new Config)->fromArray($popoData[PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG])
                        );

                    $this->configMerger->updateSchemaConfigFromCommandConfiguration($popoSchema, $configurator);

                    $this->validate(
                        $popoSchema,
                        $popoData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY],
                        $schemaFile->getFilename()->getPathname()
                    );

                    $result[$schemaName][$popoName] = $this->buildSchemaPropertyCollection(
                        $popoSchema,
                        $popoData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY],
                    );
                }
            }
        }

        return $result;
    }

    protected function generateSharedConfig(PopoConfigurator $configurator, array $data): SchemaFile
    {
        $file = (new SchemaFile)->setSharedConfig(
            [
                PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG => [],
                PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT => [],
                PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY => [],
            ]
        );

        $schemaConfigFilename = trim((string) $configurator->getSchemaConfigFilename());
        if ($schemaConfigFilename === '') {
            return $file;
        }

        $sharedConfigFile = current(
            $this->loader->load(
                (new PopoConfigurator)->setSchemaPath($configurator->getSchemaConfigFilename())
            )
        );

        return $this->configMerger->generateSharedConfig($sharedConfigFile, $data);
    }

    protected function buildSchemaPropertyCollection(Schema $schema, array $propertyCollection): Schema
    {
        $propertyCollection = $this->sortPropertyCollectionByName($propertyCollection);

        $properties = [];
        foreach ($propertyCollection as $propertyData) {
            $properties[] = $this->buildProperty($schema, $propertyData);
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

    protected function sortPropertyCollectionByName(array $propertyCollection): array
    {
        usort(
            $propertyCollection,
            function (mixed $a, mixed $b) {
                return strcasecmp(
                    $a[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY_NAME],
                    $b[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY_NAME]
                );
            }
        );

        return $propertyCollection;
    }

    /**
     * @param \Popo\Schema\Schema $popoSchema
     * @param array $propertyCollection
     * @param string $filename
     *
     * @return void
     * @throws \RuntimeException
     */
    protected function validate(Schema $popoSchema, array $propertyCollection, string $filename): void
    {
        $names = [];
        foreach ($propertyCollection as $property) {
            if (array_key_exists($property[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY_NAME], $names)) {
                throw new RuntimeException(
                    sprintf(
                        'Property with name "%s" is already defined and cannot be used for "%s::%s" in "%s"',
                        $property['name'],
                        $popoSchema->getSchemaName(),
                        $popoSchema->getName(),
                        $filename
                    )
                );
            }

            $names[$property['name']] = true;
        }
    }
}
