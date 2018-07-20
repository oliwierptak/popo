<?php

declare(strict_types = 1);

namespace Popo\Writer;

interface WriterInterface
{
    /**
     * @param \Popo\Schema\Bundle\BundleSchemaInterface[] $schemaFiles
     * @param string $extension
     * @param string $outputDirectory
     *
     * @return void
     */
    public function write(array $schemaFiles, string $extension, string $outputDirectory): void;
}
