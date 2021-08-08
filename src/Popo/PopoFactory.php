<?php

declare(strict_types = 1);

namespace Popo;

use JetBrains\PhpStorm\Pure;
use Popo\Builder\PopoBuilder;
use Popo\Loader\FileLocator;
use Popo\Loader\Yaml\YamlLoader;
use Popo\Model\Report\ReportModel;
use Popo\Schema\ConfigMerger;
use Popo\Schema\SchemaInspector;
use Popo\Model\Generate\GenerateModel;
use Popo\Builder\SchemaBuilder;
use Popo\Loader\SchemaLoader;
use Symfony\Component\Finder\Finder;

class PopoFactory
{
    public function createPopoModel(): GenerateModel
    {
        return new GenerateModel(
            $this->createSchemaBuilder(),
            $this->createPopoBuilder(),
        );
    }

    public function createReportModel(): ReportModel
    {
        return new ReportModel(
            $this->createSchemaLoader()
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
