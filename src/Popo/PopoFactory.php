<?php

declare(strict_types = 1);

namespace Popo;

use JetBrains\PhpStorm\Pure;
use Popo\Builder\PopoBuilder;
use Popo\Builder\TestBuilder;
use Popo\Loader\Finder\FileLoader;
use Popo\Schema\SchemaInspector;
use Popo\Model\PopoModel;
use Popo\Builder\SchemaBuilder;
use Popo\Loader\SchemaLoader;
use Popo\Model\TestModel;
use Symfony\Component\Finder\Finder;

class PopoFactory
{
    #[Pure] public function createPopoModel(): PopoModel
    {
        return new PopoModel(
            $this->createSchemaBuilder(),
            $this->createPopoBuilder(),
            $this->createFileLoader()
        );
    }

    #[Pure] public function createTestModel(): TestModel
    {
        return new TestModel(
            $this->createSchemaBuilder(),
            $this->createTestBuilder()
        );
    }

    #[Pure] protected function createSchemaBuilder(): SchemaBuilder
    {
        return new SchemaBuilder(
            $this->createSchemaLoader()
        );
    }

    #[Pure] protected function createSchemaLoader(): SchemaLoader
    {
        return new SchemaLoader();
    }

    #[Pure] protected function createPopoBuilder(): PopoBuilder
    {
        return new PopoBuilder(
            $this->createValueTypeWriter()
        );
    }

    #[Pure] protected function createTestBuilder(): TestBuilder
    {
        return new TestBuilder(
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
