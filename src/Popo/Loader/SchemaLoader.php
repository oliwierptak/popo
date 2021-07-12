<?php

declare(strict_types = 1);

namespace Popo\Loader;

use Popo\Loader\Finder\FileLoader;
use Popo\PopoConfigurator;
use Popo\PopoDefinesInterface;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class SchemaLoader
{
    public function __construct(protected FileLoader $fileLoader)
    {
    }

    public function load(PopoConfigurator $configurator): array
    {
        $result = [];

        $files = $this->loadSchemaFiles($configurator);
        foreach ($files as $configurationFile) {
            $data = $this->loadYaml($configurationFile);

            $defaultConfig = $data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION] ??= [];
            unset($data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION]);

            $result[] = [
                PopoDefinesInterface::CONFIGURATION_SCHEMA_FILENAME => $configurationFile,
                PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG => $defaultConfig,
                PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY => $data,

            ];
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
        $files = [
            new SplFileInfo(
                $configurator->getSchemaPath()
            ),
        ];

        if (is_file($configurator->getSchemaPath()) === false) {
            $files = $this->fileLoader->load(
                $configurator->getSchemaPath(),
                $configurator->getSchemaPathFilter(),
                $configurator->getSchemaFilename()
            );
        }

        return $files;
    }

    protected function loadYaml(SplFileInfo $configurationFile): array
    {
        return Yaml::parseFile(
            $configurationFile->getPathname(),
            Yaml::PARSE_OBJECT & Yaml::PARSE_CONSTANT & Yaml::PARSE_DATETIME & Yaml::PARSE_CUSTOM_TAGS
        );
    }
}
