<?php declare(strict_types = 1);

namespace Popo\Loader;

use Symfony\Component\Finder\Finder;

class FileLocator
{
    public function __construct(protected Finder $finder)
    {
    }

    public function locate(
        string $schemaPath,
        string $schemaPathFilter,
        string $schemaFilename
    ): array {
        $finder = clone $this->finder;

        $finder
            ->in($schemaPath)
            ->path($schemaPathFilter)
            ->name($schemaFilename)
            ->files();

        $fileInfoCollection = [];

        foreach ($finder as $fileInfo) {
            /** @var \Symfony\Component\Finder\SplFileInfo $fileInfo */
            $fileInfoCollection[] = $fileInfo;
        }

        return $fileInfoCollection;
    }
}
