<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Schema\Schema;

class PopoBuilder extends AbstractBuilder
{
    /**
     * @throws \Throwable
     */
    public function build(Schema $schema): string
    {
        $this->buildSchema($schema);

        foreach ($schema->getPropertyCollection() as $property) {
            $this->runPropertyMethodPlugins($property);
        }

        $this->runClassPlugins();

        return $this->fileWriter->save($this->file, $schema);
    }
}
