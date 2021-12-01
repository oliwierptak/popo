<?php

declare(strict_types = 1);

namespace Popo;

use LogicException;
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
use function class_exists;

class PopoFactory
{
    public function createPopoModel(PopoConfigurator $configurator): GenerateModel
    {
        return new GenerateModel(
            $this->createSchemaBuilder(),
            $this->createPopoBuilder($configurator),
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

    protected function createPopoBuilder(PopoConfigurator $configurator): PopoBuilder
    {
        return new PopoBuilder(
            $this->createSchemaInspector(),
            $this->createPlugins($configurator)
        );
    }

    protected function createSchemaInspector(): SchemaInspector
    {
        return new SchemaInspector();
    }

    protected function createFileLocator(): FileLocator
    {
        return new FileLocator(Finder::create());
    }

    protected function createLoader(): YamlLoader
    {
        return new YamlLoader();
    }

    protected function createConfigMerger(): ConfigMerger
    {
        return new ConfigMerger();
    }

    protected function createPlugins(PopoConfigurator $configurator): array
    {
        $result = [];
        foreach ($configurator->getPluginClasses() as $pluginClassName) {
            if (!class_exists($pluginClassName)) {
                throw new LogicException('Invalid plugin class name: ' . $pluginClassName);
            }

            $result[] = new $pluginClassName(
                $this->createSchemaInspector()
            );
        }

        return $result;
    }
}
