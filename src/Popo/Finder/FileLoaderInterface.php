<?php

declare(strict_types = 1);

namespace Popo\Finder;

interface FileLoaderInterface
{
    /**
     * @param string $schemaDirectory
     * @param string|null $schemaPath
     * @param string|null $schemaFilename
     *
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    public function load(
        string $schemaDirectory,
        ?string $schemaPath = '@^(.*)/src/(.*)/Schema/(.*)$@',
        ?string $schemaFilename = '*.schema.json'
    ): array;
}
