<?php

declare(strict_types = 1);

namespace Popo\Loader;

use JetBrains\PhpStorm\ArrayShape;
use Popo\PopoConfigurator;
use Popo\PopoDefinesInterface;
use Popo\Schema\SchemaFile;
use RuntimeException;
use SplFileInfo;
use function array_key_exists;

class SchemaLoader
{
    public function __construct(protected FileLocator $fileLocator, protected LoaderInterface $loader)
    {
    }

    public function loadSharedConfig(?string $schemaConfigFilename): SchemaFile
    {
        $file = (new SchemaFile)->setFileConfig(PopoDefinesInterface::SCHEMA_DEFAULT_DATA);

        if (trim((string)$schemaConfigFilename) === '') {
            return $file;
        }

        return current(
            $this->load(
                (new PopoConfigurator)->setSchemaPath($schemaConfigFilename)
            )
        );
    }

    /**
     * @param \Popo\PopoConfigurator $configurator
     * @param bool $remapProperties
     *
     * @return SchemaFile[]
     */
    #[ArrayShape([SchemaFile::class])] public function load(PopoConfigurator $configurator, bool $remapProperties = true): array
    {
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

        $files = [
            new SplFileInfo($configurator->getSchemaPath()),
        ];

        if (is_file($configurator->getSchemaPath()) === false) {
            $files = $this->fileLocator->locate(
                $configurator->getSchemaPath(),
                (string) $configurator->getSchemaPathFilter(),
                $configurator->getSchemaFilename()
            );
        }

        return $files;
    }

    protected function validate(PopoConfigurator $configurator): void
    {
        $this->validatePath($configurator->getSchemaPath());
        if (trim((string) $configurator->getSchemaConfigFilename()) !== '') {
            $this->validatePath($configurator->getSchemaConfigFilename());
        }
    }

    protected function validatePath(string $path): void
    {
        if (is_file($path) === false && is_dir($path) === false) {
            throw new RuntimeException(sprintf('Specified path to POPO schema does not exist: "%s"', $path));
        }
    }

    protected function hasSchemaConfigOption(array $schemaData): bool
    {
        return array_key_exists(
            PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION_SYMBOL,
            $schemaData,
        );
    }

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

    protected function remapProperties(array $data): array
    {
        $propertyDataCollection = $data[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY] ?? [];

        $properties = [];
        foreach ($propertyDataCollection as $propertyData) {
            $properties[$propertyData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY_NAME]] = $propertyData;
        }

        return $properties;
    }

    protected function extractConfig(array $data, bool $remap): array
    {
        $fileConfig = $data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION_SYMBOL] ?? [];
        if ($remap) {
            $fileConfig[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY] = $this->remapProperties($fileConfig);
        }

        return $fileConfig;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function removeOptionSymbol(array $data): array
    {
        unset($data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION_SYMBOL]);

        return $data;
    }
}
