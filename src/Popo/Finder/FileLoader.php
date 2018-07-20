<?php

declare(strict_types = 1);

namespace Popo\Finder;

use Symfony\Component\Finder\Finder;

class FileLoader implements FileLoaderInterface
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    public function load(
        string $schemaDirectory,
        ?string $schemaPath = '@^(.*)/src/(.*)/Schema/(.*)$@',
        ?string $schemaFilename = '*.schema.json'
    ): array {
        $finder = clone $this->finder;

        $finder
            ->in($schemaDirectory)
            ->name($schemaFilename)
            ->path($schemaPath)
            ->files();

        $fileInfoCollection = [];
        foreach ($finder as $fileInfo) {
            /** @var \Symfony\Component\Finder\SplFileInfo $fileInfo */
            $fileInfoCollection[] = $fileInfo;
        }

        return $fileInfoCollection;
    }
}
