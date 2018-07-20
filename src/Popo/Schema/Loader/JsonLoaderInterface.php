<?php

declare(strict_types = 1);

namespace Popo\Schema\Loader;

interface JsonLoaderInterface
{
    /**
     * @param string $schemaFilename
     *
     * @throws \Popo\Schema\Exception\SchemaLoaderException
     *
     * @return array
     */
    public function load(string $schemaFilename): array;
}
