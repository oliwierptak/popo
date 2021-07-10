<?php

declare(strict_types = 1);

namespace Popo\Model;

use Popo\Builder\PopoBuilder;
use Popo\Builder\SchemaBuilder;
use function fwrite;
use const POPO_TESTS_DIR;

class PopoModel
{
    public function __construct(protected SchemaBuilder $schemaBuilder, protected PopoBuilder $clasBuilder)
    {
    }

    public function generate(array $files): void
    {
        $data = $this->schemaBuilder->build($files);

        foreach ($data as $schemaName => $schemaCollection) {
            foreach ($schemaCollection as $popoName => $popoSchema) {
                /** @var \Popo\Schema\Schema $popoSchema $class */
                $this->clasBuilder->build($popoSchema);

                foreach ($popoSchema->getPropertyCollection() as $property) {
                    $this->clasBuilder
                        ->addProperty($property)
                        ->addGetMethod($property)
                        ->addSetMethod($property)
                        ->addParameter($property);
                }

                $this->clasBuilder
                    ->addSchemaShapeConstant()
                    ->addToArrayMethod()
                    ->addFromArrayMethod();

                $popoSchema->setGenerated($this->clasBuilder->print());

                $h = fopen(POPO_TESTS_DIR . 'App/Popo/' . $schemaName . '/' . $popoName . '.php', 'w');
                fwrite($h, $popoSchema->getGenerated());
                fclose($h);
            }
        }
    }
}
