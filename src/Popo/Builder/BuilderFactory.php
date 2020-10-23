<?php declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Configurator;
use Popo\Generator\GeneratorFactory;
use Popo\Plugin\PluginContainer;
use Popo\Schema\Loader\LoaderFactory;
use Popo\Schema\SchemaFactory;

class BuilderFactory
{
    protected LoaderFactory $loaderFactory;
    protected GeneratorFactory $generatorFactory;
    protected SchemaFactory $schemaFactory;

    public function __construct(
        LoaderFactory $loaderFactory,
        GeneratorFactory $generatorFactory,
        SchemaFactory $schemaFactory
    ) {
        $this->loaderFactory = $loaderFactory;
        $this->generatorFactory = $generatorFactory;
        $this->schemaFactory = $schemaFactory;
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
    ): PluginContainer {
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
