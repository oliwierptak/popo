<?php

declare(strict_types = 1);

namespace Popo\Model;

use Popo\Builder\PopoBuilder;
use Popo\Builder\SchemaBuilder;
use Popo\Loader\Finder\FileLoader;
use Popo\PopoConfigurator;
use Symfony\Component\Finder\SplFileInfo;

class PopoModel
{
    public function __construct(
        protected SchemaBuilder $schemaBuilder,
        protected PopoBuilder $popoBuilder,
        protected FileLoader $fileLoader
    ) {
    }

    public function generate(PopoConfigurator $configurator): void
    {
        $files = [
            new SplFileInfo(
                $configurator->getSchemaPath(), '', ''
            ),
        ];

        if (is_file($configurator->getSchemaPath()) === false) {
            $files = $this->fileLoader->load(
                $configurator->getSchemaPath()
            );
        }

        $data = $this->schemaBuilder->build($files);

        foreach ($data as $schemaName => $schemaCollection) {
            foreach ($schemaCollection as $popoName => $popoSchema) {
                $this->popoBuilder->build($popoSchema);
            }
        }
    }
}
