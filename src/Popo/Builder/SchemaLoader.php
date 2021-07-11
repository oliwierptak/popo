<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\PopoDefinesInterface;
use Symfony\Component\Yaml\Yaml;

class SchemaLoader
{
    public function load(array $files): array
    {
        $result = [];

        foreach ($files as $configurationFile) {
            $data = Yaml::parseFile(
                $configurationFile,
                Yaml::PARSE_OBJECT & Yaml::PARSE_CONSTANT & Yaml::PARSE_DATETIME & Yaml::PARSE_CUSTOM_TAGS
            );
            $defaultConfig = $this->extractDefaultConfig($data);
            unset($data[PopoDefinesInterface::CONFIGURATION_SCHEMA_SYMBOL]);
            unset($data[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION]);

            $propertyData = $data;
            unset($propertyData[PopoDefinesInterface::CONFIGURATION_SCHEMA_OPTION]);

            $result[] = [
                PopoDefinesInterface::CONFIGURATION_SCHEMA_FILENAME => $configurationFile,
                PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG => $defaultConfig,
                PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY => $propertyData,

            ];
        }

        return $result;
    }

    protected function extractDefaultConfig(mixed $data): array
    {
        if ($data === false) {
            $data[PopoDefinesInterface::CONFIGURATION_SCHEMA_SYMBOL] = [];
        }

        return $data[PopoDefinesInterface::CONFIGURATION_SCHEMA_SYMBOL];
    }
}
