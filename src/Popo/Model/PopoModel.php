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
        $files = [$configurator->getConfigFile()];
        $data = $this->schemaBuilder->build($files);

        foreach ($data as $schemaName => $schemaCollection) {
            foreach ($schemaCollection as $popoName => $popoSchema) {
                /** @var \Popo\Schema\Schema $popoSchema $class */
                $popoSchema->setConfigurator($configurator);

                $this->popoBuilder->build($popoSchema);

                foreach ($popoSchema->getPropertyCollection() as $property) {
                    $this->popoBuilder
                        ->addProperty($property)
                        ->addRequireByMethod($property)
                        ->addSetMethod($property)
                        ->addParameter($property)
                        ->addGetMethod($property)
                        ->addHasPropertyValueMethod($property);
                }

                $this->popoBuilder
                    ->addMetadataShapeConstant()
                    ->addToArrayMethod()
                    ->addFromArrayMethod()
                    ->addUpdateMap()
                    ->addIsNewMethod();

                $popoSchema->setGenerated($this->popoBuilder->print());

                $this->popoBuilder->save();
            }
        }
    }
}
