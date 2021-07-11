<?php

declare(strict_types = 1);

namespace Popo\Model;

use Popo\Builder\PopoBuilder;
use Popo\Builder\SchemaBuilder;
use Popo\PopoConfigurator;

class PopoModel
{
    public function __construct(protected SchemaBuilder $schemaBuilder, protected PopoBuilder $popoBuilder)
    {
    }

    public function generate(PopoConfigurator $configurator): void
    {
        $files = [$configurator->getSchemaPath()];
        $data = $this->schemaBuilder->build($files);

        foreach ($data as $schemaName => $schemaCollection) {
            foreach ($schemaCollection as $popoName => $popoSchema) {
                $this->popoBuilder->build($popoSchema);
            }
        }
    }
}
