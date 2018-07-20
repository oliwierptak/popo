<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Generator\GeneratorFactoryInterface;
use Popo\Schema\Loader\LoaderFactoryInterface;
use Popo\Schema\SchemaFactoryInterface;
use Popo\Writer\WriterFactoryInterface;

class BuilderFactory implements BuilderFactoryInterface
{
    /**
     * @var \Popo\Schema\Loader\LoaderFactoryInterface
     */
    protected $loaderFactory;

    /**
     * @var \Popo\Generator\GeneratorFactoryInterface
     */
    protected $generatorFactory;

    /**
     * @var \Popo\Schema\SchemaFactoryInterface
     */
    protected $schemaFactory;

    /**
     * @var \Popo\Writer\WriterFactoryInterface
     */
    protected $writerFactory;

    public function __construct(
        LoaderFactoryInterface $loaderFactory,
        GeneratorFactoryInterface $generatorFactory,
        SchemaFactoryInterface $schemaFactory,
        WriterFactoryInterface $writerFactory
    ) {
        $this->loaderFactory = $loaderFactory;
        $this->generatorFactory = $generatorFactory;
        $this->schemaFactory = $schemaFactory;
        $this->writerFactory = $writerFactory;
    }

    public function createBuilder(): GeneratorBuilderInterface
    {
        $generatorBuilder = new GeneratorBuilder(
            $this->loaderFactory->createContentLoader(),
            $this->generatorFactory,
            $this->schemaFactory
        );

        return $generatorBuilder;
    }

    public function createBuilderWriter(): BuilderWriterInterface
    {
        return new BuilderWriter(
            $this->schemaFactory->createSchemaBuilder(),
            $this->schemaFactory->createSchemaMerger(),
            $this->writerFactory
        );
    }

    public function createPluginContainer(BuilderConfiguratorInterface $configurator): PluginContainerInterface
    {
        $pluginContainer = new PluginContainer(
            $this->schemaFactory->createPropertyExplorer()
        );

        $pluginContainer->registerSchemaClassPlugins($configurator->getSchemaPluginClasses());
        $pluginContainer->registerPropertyClassPlugins($configurator->getPropertyPluginClasses());

        return $pluginContainer;
    }
}
