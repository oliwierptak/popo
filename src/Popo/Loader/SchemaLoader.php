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

    /**
     * @param \Popo\PopoConfigurator $configurator
     *
     * @return SchemaFile[]
     */
    #[ArrayShape([SchemaFile::class])] public function load(PopoConfigurator $configurator): array
    {
        $result = [];
        $files = $this->loadSchemaFiles($configurator);

        foreach ($files as $configurationFile) {
            $schemaConfig = [];
            $data = $this->loader->load($configurationFile);

            $extractedConfigData = $this->extractSharedSchemaConfiguration($data);

            foreach ($extractedConfigData[PopoDefinesInterface::CONFIGURATION_SCHEMA_DATA] as $schemaName => $schemaData) {
                if ($this->hasSchemaConfigOption($schemaData)) {
                    $schemaConfig[$schemaName] = $this->extractSharedSchemaConfiguration($schemaData);

                    unset($schemaConfig[$schemaName][PopoDefinesInterface::CONFIGURATION_SCHEMA_DATA]);
                    unset($extractedConfigData[PopoDefinesInterface::CONFIGURATION_SCHEMA_DATA][$schemaName][PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION]);
                }
            }

            $schemaFileData = $extractedConfigData[PopoDefinesInterface::CONFIGURATION_SCHEMA_DATA];
            unset($extractedConfigData[PopoDefinesInterface::CONFIGURATION_SCHEMA_DATA]);

            $result[] = (new SchemaFile)
                ->setFilename($configurationFile)
                ->setSharedConfig($extractedConfigData)
                ->setSchemaConfig($schemaConfig)
                ->setData($schemaFileData);
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

    #[ArrayShape([
        PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG => "array",
        PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT => "array",
        PopoDefinesInterface::CONFIGURATION_SCHEMA_DATA => "array",
        PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY => "array",
    ])] protected function extractSharedSchemaConfiguration(
        array $data
    ): array {
        $config = $data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION] ?? [];
        $defaults = $config[PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT] ?? [];
        $properties = $config[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY] ?? [];

        unset($config[PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT]);
        unset($config[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY]);
        unset($data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION]);

        return [
            PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG => $config,
            PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT => $defaults,
            PopoDefinesInterface::CONFIGURATION_SCHEMA_DATA => $data,
            PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY => $properties,
        ];
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
            PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION,
            $schemaData,
        );
    }
}
