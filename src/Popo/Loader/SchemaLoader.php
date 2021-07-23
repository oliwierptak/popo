<?php

declare(strict_types = 1);

namespace Popo\Loader;

use JetBrains\PhpStorm\ArrayShape;
use Popo\PopoConfigurator;
use Popo\PopoDefinesInterface;
use Popo\Schema\SchemaFile;
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
        $sharedConfig = [];
        $files = $this->loadSchemaFiles($configurator);

        foreach ($files as $configurationFile) {
            $data = $this->loader->load($configurationFile);
            $extractedData = $this->extractConfigAndPropertyData($data);

            foreach ($extractedData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY] as $schemaName => $schemaData) {
                if (array_key_exists(
                    PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION,
                    $extractedData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY][$schemaName]
                )) {
                    $sharedConfig[$schemaName] = $this->extractConfigAndPropertyData(
                        $extractedData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY][$schemaName]
                    );
                    unset($extractedData[PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY][$schemaName][PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION]);
                }
            }

            $result[] = (new SchemaFile)
                ->setFilename($configurationFile)
                ->setSharedConfig($sharedConfig)
                ->setData($extractedData);
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
        PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY => "array",
    ])] protected function extractConfigAndPropertyData(
        array $data
    ): array {
        $config = $data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION] ?? [];
        unset($data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION]);

        return [
            PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG => $config,
            PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY => $data,

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
            throw new \RuntimeException(sprintf('Specified path to POPO schema does not exist: "%s"', $path));
        }
    }
}
