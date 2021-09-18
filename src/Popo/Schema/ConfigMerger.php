<?php

declare(strict_types = 1);

namespace Popo\Schema;

use JetBrains\PhpStorm\Pure;
use Popo\PopoDefinesInterface;
use function array_replace_recursive;

class ConfigMerger
{
    #[Pure] public function mergeSchemaConfiguration(string $schemaName, SchemaFile $sharedSchemaFile, SchemaFile $schemaFile): array
    {
        $sharedConfig = $this->mergeSchemaFile(
            $schemaName,
            $sharedSchemaFile
        );
        $fileConfig = $this->mergeSchemaFile(
            $schemaName,
            $schemaFile
        );

        return $this->mergeSchemaDefaults(
            $sharedConfig,
            $fileConfig
        );
    }

    #[Pure] public function mergeSchemaFile(string $schemaName, SchemaFile $file): array
    {
        return array_replace_recursive(
            $file->getFileConfig(),
            $file->getSchemaConfig()[$schemaName] ?? []
        );
    }

    public function mergeSchemaDefaults(...$data): array
    {
        return array_replace_recursive(
            PopoDefinesInterface::SCHEMA_DEFAULT_DATA,
            ...$data
        );
    }

    #[Pure] public function mergePopoCollection(
        string $schemaName,
        array $popoCollection,
        array $configData,
        array $result
    ): array {
        foreach ($popoCollection as $popoName => $popoData) {
            $result[$schemaName][$popoName] = array_replace_recursive(
                PopoDefinesInterface::SCHEMA_DEFAULT_DATA,
                $result[$schemaName][$popoName] ?? [],
                $configData,
                $popoData
            );
        }

        return $result;
    }
}
