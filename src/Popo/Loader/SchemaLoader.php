<?php

declare(strict_types = 1);

namespace Popo\Loader;

use JetBrains\PhpStorm\ArrayShape;
use Popo\PopoDefinesInterface;
use Symfony\Component\Yaml\Yaml;

class SchemaLoader
{
    /**
     * @param \Symfony\Component\Finder\SplFileInfo[] $files
     *
     * @return array
     */
    #[ArrayShape(PopoDefinesInterface::SCHEMA_LOADER_BUILD_SHAPE)]
    public function load(array $files): array
    {
        $result = [];

        foreach ($files as $configurationFile) {
            $data = Yaml::parseFile(
                $configurationFile->getPathname(),
                Yaml::PARSE_OBJECT & Yaml::PARSE_CONSTANT & Yaml::PARSE_DATETIME & Yaml::PARSE_CUSTOM_TAGS
            );
            $defaultConfig = $this->extractDefaultConfig($data);
            unset($data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION]);

            $result[] = [
                PopoDefinesInterface::CONFIGURATION_SCHEMA_FILENAME => $configurationFile,
                PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG => $defaultConfig,
                PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY => $data,

            ];
        }

        return $result;
    }

    protected function extractDefaultConfig(mixed $data): array
    {
        if ($data === false) {
            $data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION] = [];
        }

        return $data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION];
    }
}
