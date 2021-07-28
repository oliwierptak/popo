<?php

declare(strict_types = 1);

namespace Popo\Model;

use Popo\Builder\PopoBuilder;
use Popo\Builder\SchemaBuilder;
use Popo\PopoConfigurator;

class PopoModel
{
    public function __construct(
        protected SchemaBuilder $schemaBuilder,
        protected PopoBuilder $popoBuilder
    ) {
    }

    public function generate(PopoConfigurator $configurator): PopoGenerateResult
    {
        $result = new PopoGenerateResult;

        $data = $this->schemaBuilder->build($configurator);

        foreach ($data as $schemaName => $schemaCollection) {
            foreach ($schemaCollection as $popoName => $popoSchema) {
                /** @var \Popo\Schema\Schema $popoSchema */
                $filename = $this->popoBuilder->build($popoSchema);

                $result->add([
                    'filename' => $filename,
                    'schemaName' => $schemaName,
                    'popoName' => $popoName,
                    'namespace' => $popoSchema->getConfig()->getNamespace()
                ]);
            }
        }

        return $result;
    }
}
