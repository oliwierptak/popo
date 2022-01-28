<?php

declare(strict_types = 1);

namespace Popo\Schema\Config;

use Popo\PopoDefinesInterface;
use Popo\Schema\File\SchemaFile;
use function array_replace_recursive;

class ConfigMerger
{
    public function mergeSchemaConfiguration(
        string $schemaName,
        SchemaFile $sharedSchemaFile,
        SchemaFile $schemaFile
    ): array {
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

    public function mergeSchemaFile(string $schemaName, SchemaFile $file): array
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

    public function mergePopoCollection(
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
