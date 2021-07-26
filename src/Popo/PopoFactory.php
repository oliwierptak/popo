<?php

declare(strict_types = 1);

namespace Popo;

use JetBrains\PhpStorm\Pure;
use Popo\Builder\PopoBuilder;
use Popo\Loader\FileLocator;
use Popo\Loader\Yaml\YamlLoader;
use Popo\Schema\ConfigMerger;
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
            $this->createSchemaLoader(),
            $this->createConfigMerger()
        );
    }

    protected function createSchemaLoader(): SchemaLoader
    {
        return new SchemaLoader(
            $this->createFileLocator(),
            $this->createLoader()
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

    protected function createFileLocator(): FileLocator
    {
        return new FileLocator(Finder::create());
    }

    #[Pure] protected function createLoader(): YamlLoader
    {
        return new YamlLoader();
    }

    #[Pure] private function createConfigMerger(): ConfigMerger
    {
        return new ConfigMerger();
    }
}
