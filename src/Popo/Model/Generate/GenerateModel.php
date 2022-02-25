<?php

declare(strict_types = 1);

namespace Popo\Model\Generate;

use Popo\Builder\PopoGenerator;
use Popo\Builder\SchemaBuilder;
use Popo\PopoConfigurator;

class GenerateModel
{
    protected SchemaBuilder $schemaBuilder;
    protected PopoGenerator $popoGenerator;

    public function __construct(SchemaBuilder $schemaBuilder, PopoGenerator $popoGenerator)
    {
        $this->schemaBuilder = $schemaBuilder;
        $this->popoGenerator = $popoGenerator;
    }

    public function generate(PopoConfigurator $configurator): GenerateResult
    {
        $result = new GenerateResult;
        $data = $this->schemaBuilder->build($configurator);

        foreach ($data as $schemaName => $schemaCollection) {
            foreach ($schemaCollection as $popoName => $popoSchema) {
                $filename = $this->popoGenerator->generate($popoSchema);

                $result->add([
                    'filename' => $filename,
                    'schemaName' => $schemaName,
                    'popoName' => (string) $popoName,
                    'namespace' => $popoSchema->getConfig()->getNamespace(),
                ]);
            }
        }

        return $result;
    }
}
