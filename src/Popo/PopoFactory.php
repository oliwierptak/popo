<?php

declare(strict_types = 1);

namespace Popo;

use JetBrains\PhpStorm\Pure;
use Popo\Builder\PopoBuilder;
use Popo\Loader\Finder\FileLoader;
use Popo\Schema\SchemaInspector;
use Popo\Model\PopoModel;
use Popo\Builder\SchemaBuilder;
use Popo\Loader\SchemaLoader;
use Symfony\Component\Finder\Finder;

class PopoFactory
{
    public function createPopoModel(): PopoModel
    {
        return new PopoModel(
            $this->createSchemaBuilder(),
            $this->createPopoBuilder(),
        );
    }

    protected function createSchemaBuilder(): SchemaBuilder
    {
        return new SchemaBuilder(
            $this->createSchemaLoader()
        );
    }

    protected function createSchemaLoader(): SchemaLoader
    {
        return new SchemaLoader(
            $this->createFileLoader()
        );
    }

    #[Pure] protected function createPopoBuilder(): PopoBuilder
    {
        return new PopoBuilder(
            $this->createValueTypeWriter()
        );
    }

    #[Pure] protected function createValueTypeWriter(): SchemaInspector
    {
        return new SchemaInspector();
    }

    protected function createFileLoader(): FileLoader
    {
        return new FileLoader(Finder::create());
    }
}
