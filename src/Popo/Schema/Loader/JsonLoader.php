<?php declare(strict_types = 1);

namespace Popo\Schema\Loader;

use Popo\Schema\Exception\SchemaLoaderException;
use Throwable;
use function file_get_contents;
use function json_decode;
use function sprintf;

class JsonLoader
{
    /**
     * @param string $schemaFilename
     *
     * @return array
     * @throws \Popo\Schema\Exception\SchemaLoaderException
     *
     */
    public function load(string $schemaFilename): array
    {
        try {
            $json = file_get_contents($schemaFilename);

            return json_decode($json, true);
        }
        catch (Throwable $e) {
            throw new SchemaLoaderException(
                sprintf(
                    'Error loading schema file: "%s"',
                    $schemaFilename
                )
            );
        }
    }
}
