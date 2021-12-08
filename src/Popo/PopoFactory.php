<?php

declare(strict_types = 1);

namespace Popo;

use LogicException;
use Popo\Builder\FileWriter;
use Popo\Builder\PopoBuilder;
use Popo\Builder\SchemaBuilder;
use Popo\Loader\FileLocator;
use Popo\Loader\SchemaLoader;
use Popo\Loader\Yaml\YamlLoader;
use Popo\Model\Generate\GenerateModel;
use Popo\Model\Report\ReportModel;
use Popo\Schema\ConfigMerger;
use Popo\Schema\SchemaInspector;
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
            $this->createFileWriter(),
            $this->createClassPlugins($configurator),
            $this->createPropertyMethodClassPlugins($configurator),
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

    protected function createClassPlugins(PopoConfigurator $configurator): array
    {
        $result = [];
        foreach ($configurator->getClassPluginCollection() as $pluginClassName) {
            if (!class_exists($pluginClassName)) {
                throw new LogicException('Invalid plugin class name: ' . $pluginClassName);
            }

            $result[] = new $pluginClassName();
        }

        return $result;
    }

    protected function createPropertyMethodClassPlugins(PopoConfigurator $configurator): array
    {
        $result = [];
        foreach ($configurator->getPropertyMethodPluginCollection() as $pluginClassName) {
            if (!class_exists($pluginClassName)) {
                throw new LogicException('Invalid plugin class name: ' . $pluginClassName);
            }

            $result[] = new $pluginClassName();
        }

        return $result;
    }

    protected function createFileWriter(): FileWriter
    {
        return new FileWriter();
    }
}
