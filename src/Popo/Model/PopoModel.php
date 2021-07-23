<?php

declare(strict_types = 1);

namespace Popo\Model;

use Popo\Builder\PopoBuilder;
use Popo\Builder\SchemaBuilder;
use Popo\PopoConfigurator;
use Popo\PopoResult;
use Popo\Schema\ConfigMerger;

class PopoModel
{
    public function __construct(
        protected SchemaBuilder $schemaBuilder,
        protected PopoBuilder $popoBuilder
    ) {
    }

    public function generate(PopoConfigurator $configurator): PopoResult
    {
        $result = new PopoResult;

        $data = $this->schemaBuilder->build($configurator);

        foreach ($data as $schemaName => $schemaCollection) {
            foreach ($schemaCollection as $popoName => $popoSchema) {
                /** @var \Popo\Schema\Schema $popoSchema */
                $filename = $this->popoBuilder->build($popoSchema);
                $entry = sprintf(
                    '<fg=yellow>%s:</><fg=green>%s\%s</> -> <fg=green>%s</>',
                    $schemaName,
                    $popoSchema->getConfig()->getNamespace(),
                    $popoName,
                    $filename,
                );
                $result->add($entry);
            }
        }

        return $result;
    }
}
