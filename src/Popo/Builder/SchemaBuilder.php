<?php

declare(strict_types = 1);

namespace Popo\Builder;

use JetBrains\PhpStorm\ArrayShape;
use Popo\PopoDefinesInterface;
use Popo\Schema\Config;
use Popo\Schema\Schema;
use Popo\Schema\Property;

class SchemaBuilder
{
    protected const SCHEMA_SHAPE = [Schema::class];

    public function __construct(protected SchemaLoader $loader)
    {
    }

    #[ArrayShape(self::SCHEMA_SHAPE)]
    public function build(
        array $files
    ): array {
        $data = $this->loader->load($files);

        $result = [];
        foreach ($data as $schemaData) {
            $defaultConfig = (new Config)->fromArray(
                $schemaData[PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG]
            );

            foreach ($schemaData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY] as $schemaName => $popoCollection) {
                foreach ($popoCollection as $popoName => $popoData) {
                    $popoConfig = (new Config)->fromArray(
                        array_merge(
                            $defaultConfig->toArray(),
                            $popoData[PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG] ?? [],
                        )
                    )->setDefaultConfig($defaultConfig);

                    $popoSchema = (new Schema)
                        ->setName($popoName)
                        ->setSchemaName($schemaName)
                        ->setConfig($popoConfig);

                    $result[$schemaName][$popoName] = $this->buildPopoSchema(
                        $popoSchema,
                        $popoConfig,
                        $popoData
                    );
                }
            }
        }

        return $result;
    }

    protected function buildPopoSchema(
        Schema $popoSchema,
        Config $config,
        array $popoSchemaData
    ): Schema {
        $propertyCollection = $popoSchemaData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY] ?? [];
        $propertyDefaultConfig = $popoSchemaData[PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT] ?? [];

        $properties = [];
        foreach ($propertyCollection as $propertyData) {
            $propertySchema = (new Property)
                ->fromArray($propertyData);

            $default = $propertySchema->getDefault() ??
                $propertyDefaultConfig[$propertySchema->getName()] ??
                $config->getDefault()[$propertySchema->getName()] ??
                null;

            $propertySchema
                ->setDefault($default);

            $properties[] = $propertySchema;
        }

        $popoSchema->setPropertyCollection($properties);

        return $popoSchema;
    }
}
