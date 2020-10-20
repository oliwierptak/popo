<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Configurator;
use Popo\Generator\GeneratorFactory;
use Popo\Plugin\PluginContainer;
use Popo\Schema\Loader\LoaderFactory;
use Popo\Schema\SchemaFactory;
use Popo\Writer\WriterFactory;

class BuilderFactory
{
    protected LoaderFactory $loaderFactory;

    protected GeneratorFactory $generatorFactory;

    protected SchemaFactory $schemaFactory;

    protected WriterFactory $writerFactory;

    public function __construct(LoaderFactory $loaderFactory, GeneratorFactory $generatorFactory, SchemaFactory $schemaFactory, WriterFactory $writerFactory)
    {
        $this->loaderFactory = $loaderFactory;
        $this->generatorFactory = $generatorFactory;
        $this->schemaFactory = $schemaFactory;
        $this->writerFactory = $writerFactory;
    }

    public function createPopoGeneratorBuilder(): PopoGeneratorBuilder
    {
        return new PopoGeneratorBuilder(
            $this->loaderFactory->createContentLoader(),
            $this->generatorFactory,
            $this->schemaFactory
        );
    }

    public function createPluginContainer(Configurator $configurator): PluginContainer
    {
        $pluginContainer = new PluginContainer(
            $this->schemaFactory->createPropertyExplorer()
        );

        $pluginContainer = $this->registerPlugins($pluginContainer, $configurator);

        return $pluginContainer;
    }

    protected function registerPlugins(
        PluginContainer $pluginContainer,
        Configurator $configurator
    ): PluginContainer
    {
        $pluginContainer->registerSchemaClassPlugins(
            $configurator->getSchemaPluginClasses()
        );

        $pluginContainer->registerArrayableClassPlugins(
            $configurator->getArrayablePluginClasses()
        );

        $pluginContainer->registerPropertyClassPlugins(
            $configurator->getPropertyPluginClasses()
        );

        $pluginContainer->registerCollectionClassPlugins(
            $configurator->getCollectionPluginClasses()
        );

        return $pluginContainer;
    }
}
