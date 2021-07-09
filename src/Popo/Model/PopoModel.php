<?php

declare(strict_types = 1);

namespace Popo\Model;

use Popo\Builder\ClassBuilder;
use Popo\Builder\SchemaBuilder;
use function dump;

class PopoModel
{
    public function __construct(protected SchemaBuilder $schemaBuilder, protected ClassBuilder $clasBuilder)
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

                $popoSchema->setGenerated($this->clasBuilder->print());
            }
        }

        dump($data);
    }
}
