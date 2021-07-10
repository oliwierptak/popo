<?php

declare(strict_types = 1);

namespace Popo;

use JetBrains\PhpStorm\Pure;
use Popo\Builder\ClassBuilder;
use Popo\Inspector\SchemaValueInspector;
use Popo\Inspector\SchemaPropertyInspector;
use Popo\Model\PopoModel;
use Popo\Builder\SchemaBuilder;
use Popo\Builder\SchemaLoader;

class PopoFactory
{
    #[Pure] public function createPopoModel(): PopoModel
    {
        return new PopoModel(
            $this->createSchemaBuilder(),
            $this->createClassBuilder()
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

    #[Pure] protected function createClassBuilder(): ClassBuilder
    {
        return new ClassBuilder(
            $this->createValueTypeReader(),
            $this->createValueTypeWriter()
        );
    }

    #[Pure] protected function createValueTypeReader(): SchemaValueInspector
    {
        return new SchemaValueInspector();
    }

    #[Pure] protected function createValueTypeWriter(): SchemaPropertyInspector
    {
        return new SchemaPropertyInspector();
    }
}
