<?php

declare(strict_types = 1);

namespace Popo\Loader;

use InvalidArgumentException;
use Popo\PopoConfigurator;
use Popo\PopoDefinesInterface;
use Popo\Schema\File\SchemaFile;
use SplFileInfo;
use function array_key_exists;

class SchemaLoader
{
    protected FileLocator $fileLocator;
    protected LoaderInterface $loader;

    public function __construct(FileLocator $fileLocator, LoaderInterface $loader)
    {
        $this->fileLocator = $fileLocator;
        $this->loader = $loader;
    }

    public function loadSharedConfig(?string $schemaConfigFilename): SchemaFile
    {
        $file = (new SchemaFile)->setFileConfig(PopoDefinesInterface::SCHEMA_DEFAULT_DATA);

        if (trim((string) $schemaConfigFilename) === '') {
            return $file;
        }

        return current(
            $this->load(
                (new PopoConfigurator)->setSchemaPath((string) $schemaConfigFilename)
            )
        ) ?: $file;
    }

    /**
     * @param \Popo\PopoConfigurator $configurator
     * @param bool $remapProperties
     *
     * @return \Popo\Schema\File\SchemaFile[]
     */
    public function load(
        PopoConfigurator $configurator,
        bool $remapProperties = true
    ): array {
        $result = [];
        $files = $this->loadSchemaFiles($configurator);

        foreach ($files as $configurationFile) {
            $schemaConfig = [];
            $data = $this->loader->load($configurationFile);
            $fileConfig = $this->extractConfig($data, $remapProperties);
            $data = $this->removeOptionSymbol($data);

            foreach ($data as $schemaName => $schemaData) {
                if ($this->hasSchemaConfigOption($schemaData)) {
                    $schemaConfig[$schemaName] = $this->extractConfig($schemaData, $remapProperties);
                    $data[$schemaName] = $this->removeOptionSymbol($schemaData);
                }
            }

            $result[] = (new SchemaFile)
                ->setFilename($configurationFile)
                ->setFileConfig($fileConfig)
                ->setSchemaConfig($schemaConfig)
                ->setData(
                    $this->remapSchemaProperties($data)
                );
        }

        return $result;
    }

    /**
     * @param \Popo\PopoConfigurator $configurator
     *
     * @return \SplFileInfo[]
     */
    protected function loadSchemaFiles(PopoConfigurator $configurator): array
    {
        $this->validate($configurator);

        $files = [];
        foreach ($this->extractPaths($configurator->getSchemaPath()) as $path) {
            if (is_dir($path)) {
                $files = array_merge(
                    $files,
                    $this->fileLocator->locate(
                        $path,
                        (string) $configurator->getSchemaPathFilter(),
                        (string) $configurator->getSchemaFilenameMask()
                    )
                );
            }
            else {
                if (is_file($path)) {
                    $files[] = new SplFileInfo($path);
                }
            }
        }

        return $files;
    }

    protected function validate(PopoConfigurator $configurator): void
    {
        foreach ($this->extractPaths($configurator->getSchemaPath()) as $path) {
            if ($configurator->isIgnoreNonExistingSchemaFolder() === false) {
                $this->validatePath($path);
            }
        }

        if (trim((string) $configurator->getSchemaConfigFilename()) !== '') {
            $this->validatePath((string)$configurator->getSchemaConfigFilename());
        }
    }

    protected function validatePath(string $path): void
    {
        $info = new SplFileInfo($path);

        if ($info->isReadable() === false) {
            throw new InvalidArgumentException(sprintf('Specified path to POPO schema does not exist: "%s"', $path));
        }
    }

    /**
     * @param array<string, mixed> $schemaData
     *
     * @return bool
     */
    protected function hasSchemaConfigOption(array $schemaData): bool
    {
        return array_key_exists(
            PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION_SYMBOL,
            $schemaData,
        );
    }

    /**
     * @param array<string, mixed> $schemaFileData
     *
     * @return array<string, mixed>
     */
    protected function remapSchemaProperties(array $schemaFileData): array
    {
        foreach ($schemaFileData as $schemaName => $popoCollection) {
            foreach ($popoCollection as $popoName => $popoData) {
                $schemaFileData[$schemaName][$popoName][PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY] = $this
                    ->remapProperties($popoData);
            }
        }

        return $schemaFileData;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<int|string, mixed>
     */
    protected function remapProperties(array $data): array
    {
        $propertyDataCollection = $data[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY] ?? [];

        $properties = [];
        foreach ($propertyDataCollection as $propertyData) {
            $properties[$propertyData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY_NAME]] = $propertyData;
        }

        return $properties;
    }

    /**
     * @param array<string, mixed> $data
     * @param bool $remap
     *
     * @return array<string, mixed>
     */
    protected function extractConfig(array $data, bool $remap): array
    {
        $fileConfig = $data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION_SYMBOL] ?? [];
        if ($remap) {
            $fileConfig[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY] = $this->remapProperties($fileConfig);
        }

        return $fileConfig;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    protected function removeOptionSymbol(array $data): array
    {
        unset($data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION_SYMBOL]);

        return $data;
    }

    /**
     * @param string $path
     *
     * @return array<int, string>
     */
    protected function extractPaths(string $path): array
    {
        return \explode(',', $path);
    }
}
