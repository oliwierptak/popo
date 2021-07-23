<?php

declare(strict_types = 1);

namespace Popo\Loader\Yaml;

use Popo\Loader\LoaderInterface;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class YamlLoader implements LoaderInterface
{
    public function load(SplFileInfo $configurationFile): array
    {
        return Yaml::parseFile(
            $configurationFile->getPathname(),
            Yaml::PARSE_OBJECT & Yaml::PARSE_CONSTANT & Yaml::PARSE_DATETIME & Yaml::PARSE_CUSTOM_TAGS
        );
    }
}
