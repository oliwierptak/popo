<?php

declare(strict_types = 1);

namespace Popo\Model;

use Popo\Builder\PopoBuilder;
use Popo\Builder\SchemaBuilder;
use Popo\PopoConfigurator;

class PopoModel
{
    public function __construct(protected SchemaBuilder $schemaBuilder, protected PopoBuilder $clasBuilder)
    {
    }

    public function generate(PopoConfigurator $configurator): void
    {
        $files = [$configurator->getConfigFile()];
        $data = $this->schemaBuilder->build($files);

        foreach ($data as $schemaName => $schemaCollection) {
            foreach ($schemaCollection as $popoName => $popoSchema) {
                /** @var \Popo\Schema\Schema $popoSchema $class */
                $popoSchema->setConfigurator($configurator);
                $this->clasBuilder->build($popoSchema);

                foreach ($popoSchema->getPropertyCollection() as $property) {
                    $this->clasBuilder
                        ->addProperty($property)
                        ->addGetMethod($property)
                        ->addSetMethod($property)
                        ->addParameter($property);
                }

                $this->clasBuilder
                    ->addMetadataShapeConstant()
                    ->addToArrayMethod()
                    ->addFromArrayMethod();

                $popoSchema->setGenerated($this->clasBuilder->print());

                $this->clasBuilder->save();
            }
        }
    }
}
