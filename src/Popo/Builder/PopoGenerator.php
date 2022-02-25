<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Schema\Schema;

class PopoGenerator
{
    protected PopoBuilder $builder;
    protected FileWriter $fileWriter;

    public function __construct(PopoBuilder $builder, FileWriter $fileWriter)
    {
        $this->builder = $builder;
        $this->fileWriter = $fileWriter;
    }

    public function generate(Schema $schema): string
    {
        $this->builder->build($schema);

        return $this->fileWriter->save(
            $this->builder->getFile(),
            $this->builder->getSchema()
        );
    }
}
