<?php

declare(strict_types = 1);

namespace Popo\Schema\Loader;

use Popo\Schema\Exception\SchemaLoaderException;

class JsonLoader implements JsonLoaderInterface
{
    /**
     * @param string $schemaFilename
     *
     * @throws \Popo\Schema\Exception\SchemaLoaderException
     *
     * @return array
     */
    public function load(string $schemaFilename): array
    {
        try {
            $json = \file_get_contents($schemaFilename);

            return \json_decode($json, true);
        } catch (\Throwable $e) {
            throw new SchemaLoaderException(\sprintf(
                'Error loading schema file: "%s"',
                $schemaFilename
            ));
        }
    }
}
