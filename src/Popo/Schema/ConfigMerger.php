<?php

declare(strict_types = 1);

namespace Popo\Schema;

use JetBrains\PhpStorm\Pure;
use Popo\PopoConfigurator;
use Popo\PopoDefinesInterface;

class ConfigMerger
{
    #[Pure] public function mergeGlobalSchema(SchemaFile $globalConfig, string $schemaName): array
    {
        return array_merge_recursive(
            $globalConfig->getSharedConfig(),
            $globalConfig->getSchemaConfig()[$schemaName] ?? []
        );
    }

    public function generateSharedConfig(SchemaFile $sharedConfig, array $data): SchemaFile
    {
        $file = (new SchemaFile)->setSharedConfig(
            [
                PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG => [],
                PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT => [],
                PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY => [],
            ]
        );

        $sharedConfigData = [];
        $schemaConfigData = [];

        foreach ($data as $schemaFile) {
            $mergedConfig = array_merge_recursive($sharedConfig->getSchemaConfig(), $schemaFile->getSchemaConfig());
            $schemaConfigData = array_merge($mergedConfig, $schemaConfigData);

            $mergedConfig = array_merge_recursive($sharedConfig->getSharedConfig(), $schemaFile->getSharedConfig());
            $sharedConfigData = array_merge($mergedConfig, $sharedConfigData);
        }

        return $file
            ->setSharedConfig($sharedConfigData)
            ->setSchemaConfig($schemaConfigData);
    }

    #[Pure] public function mergePopoSchema(SchemaFile $schemaFile, array $schemaConfigData, array $popoData): array
    {
        $popoData = $this->mergeSchemaConfiguration(
            $schemaFile,
            $schemaConfigData,
            $popoData,
            PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG
        );
        $popoData = $this->mergeSchemaConfiguration(
            $schemaFile,
            $schemaConfigData,
            $popoData,
            PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT
        );
        $popoData = $this->mergeSchemaConfiguration(
            $schemaFile,
            $schemaConfigData,
            $popoData,
            PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY
        );

        return $popoData;
    }

    #[Pure] public function mergeSchemaConfiguration(
        SchemaFile $schemaFile,
        array $globalDefaultConfig,
        array $popoData,
        string $key
    ): array {
        $mergedConfig[$key] = array_merge(
            $schemaFile->getSharedConfig()[$key] ?? [],
            $schemaFile->getSchemaConfig()[$key] ?? []
        );

        $popoData[$key] = array_merge(
            $globalDefaultConfig[$key] ?? [],
            $mergedConfig[$key],
            $popoData[$key] ?? []
        );

        return $popoData;
    }

    public function updateSchemaConfigFromCommandConfiguration(Schema $popoSchema, PopoConfigurator $configurator): Schema
    {
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

}
