<?php

declare(strict_types = 1);

namespace Popo\Schema\Config;

use Popo\PopoDefinesInterface;
use Popo\Schema\File\SchemaFile;
use function array_replace_recursive;

class ConfigMerger
{
    /**
     * @param string $schemaName
     * @param \Popo\Schema\File\SchemaFile $sharedSchemaFile
     * @param \Popo\Schema\File\SchemaFile $schemaFile
     *
     * @return array<int|string, mixed>
     */
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

    /**
     * @param string $schemaName
     * @param \Popo\Schema\File\SchemaFile $file
     *
     * @return array<string, mixed>
     */
    public function mergeSchemaFile(string $schemaName, SchemaFile $file): array
    {
        return array_replace_recursive(
            $file->getFileConfig(),
            $file->getSchemaConfig()[$schemaName] ?? []
        );
    }

    /**
     * @param array<string,mixed> $data
     *
     * @return array<string,mixed>
     */
    public function mergeSchemaDefaults(...$data): array
    {
        return array_replace_recursive(
            PopoDefinesInterface::SCHEMA_DEFAULT_DATA,
            ...$data
        );
    }

    /**
     * @param string $schemaName
     * @param array<string, mixed> $popoCollection
     * @param array<int|string, mixed> $configData
     * @param array<string, string> $result
     *
     * @return array<string, string>
     */
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
