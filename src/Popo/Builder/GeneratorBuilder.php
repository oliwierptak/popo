<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Generator\GeneratorFactoryInterface;
use Popo\Generator\GeneratorInterface;
use Popo\Generator\Php\Plugin\ArrayableFactoryPlugin;
use Popo\Generator\Php\Plugin\CollectionFactoryPlugin;
use Popo\Generator\Php\Plugin\PropertyFactoryPlugin;
use Popo\Generator\Php\Plugin\SchemaFactoryPlugin;
use Popo\Plugin\Factory\PropertyFactoryPluginInterface;
use Popo\Plugin\Factory\SchemaFactoryPluginInterface;
use Popo\Schema\Loader\ContentLoaderInterface;
use Popo\Schema\Reader\PropertyExplorerInterface;
use Popo\Schema\SchemaFactoryInterface;

class GeneratorBuilder implements GeneratorBuilderInterface
{
    /**
     * @var \Popo\Schema\Loader\ContentLoaderInterface
     */
    protected $contentLoader;

    /**
     * @var \Popo\Generator\GeneratorFactoryInterface
     */
    protected $generatorFactory;

    /**
     * @var \Popo\Schema\SchemaFactoryInterface
     */
    protected $schemaFactory;

    public function __construct(
        ContentLoaderInterface $contentLoader,
        GeneratorFactoryInterface $generatorFactory,
        SchemaFactoryInterface $schemaFactory
    ) {
        $this->contentLoader = $contentLoader;
        $this->generatorFactory = $generatorFactory;
        $this->schemaFactory = $schemaFactory;
    }

    public function build(BuilderConfigurator $configurator, PluginContainerInterface $pluginContainer): GeneratorInterface
    {
        $container = $this->buildContainer($configurator, $pluginContainer);
        $schemaGenerator = $this->generatorFactory->createSchemaGenerator($container);

        return $schemaGenerator;
    }

    protected function buildContainer(
        BuilderConfigurator $configurator,
        PluginContainerInterface $pluginContainer
    ): BuilderContainer {
        $templateDirectory = $configurator->getTemplateDirectory();
        $schemaTemplateFilename = $configurator->getSchemaConfigurator()->getSchemaTemplateFilename();
        $arrayableTemplateFilename = $configurator->getSchemaConfigurator()->getArrayableTemplateFilename();
        $propertyTemplateFilename = $configurator->getSchemaConfigurator()->getPropertyTemplateFilename();
        $collectionTemplateFilename = $configurator->getSchemaConfigurator()->getCollectionTemplateFilename();
        $propertyExplorer = $this->schemaFactory->createPropertyExplorer();

        $container = (new BuilderContainer())
            ->setSchemaTemplateString(
                $this->getTemplateString($templateDirectory, $schemaTemplateFilename)
            )
            ->setArrayableTemplateString(
                $this->getTemplateString($templateDirectory, $arrayableTemplateFilename)
            )
            ->setPropertyTemplateString(
                $this->getTemplateString($templateDirectory, $propertyTemplateFilename)
            )
            ->setCollectionTemplateString(
                $this->getTemplateString($templateDirectory, $collectionTemplateFilename)
            )
            ->setSchemaPluginCollection(
                $this->buildSchemaPluginCollection($pluginContainer, $propertyExplorer)
            )
            ->setPropertyPluginCollection(
                $this->buildPropertyPluginCollection($pluginContainer, $propertyExplorer)
            )
            ->setArrayablePluginCollection(
                $this->buildArrayablePluginCollection($pluginContainer, $propertyExplorer)
            )
            ->setCollectionPluginCollection(
                $this->buildCollectionPluginCollection($pluginContainer, $propertyExplorer)
            );

        return $container;
    }

    protected function getTemplateString(string $templateDirectory, string $templateFilename): string
    {
        $filename = new \SplFileInfo($templateDirectory . $templateFilename);
        $schemaTemplateString = $this->contentLoader->load($filename);

        return $schemaTemplateString;
    }

    protected function createPropertyFactoryPlugin(PropertyExplorerInterface $propertyExplorer): PropertyFactoryPluginInterface
    {
        return new PropertyFactoryPlugin($propertyExplorer);
    }

    protected function createArrayableFactoryPlugin(PropertyExplorerInterface $propertyExplorer): SchemaFactoryPluginInterface
    {
        return new ArrayableFactoryPlugin($propertyExplorer);
    }

    protected function createCollectionFactoryPlugin(PropertyExplorerInterface $propertyExplorer): PropertyFactoryPluginInterface
    {
        return new CollectionFactoryPlugin($propertyExplorer);
    }

    protected function createSchemaFactoryPlugin(PropertyExplorerInterface $propertyExplorer): SchemaFactoryPluginInterface
    {
        return new SchemaFactoryPlugin($propertyExplorer);
    }

    /**
     * @param \Popo\Builder\PluginContainerInterface $pluginContainer
     * @param \Popo\Schema\Reader\PropertyExplorerInterface $propertyExplorer
     *
     * @return \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected function buildSchemaPluginCollection(
        PluginContainerInterface $pluginContainer,
        PropertyExplorerInterface $propertyExplorer
    ): array {
        $schemaPlugins = $this
            ->createSchemaFactoryPlugin($propertyExplorer)
            ->createPluginCollection();

        return \array_merge($schemaPlugins, $pluginContainer->getSchemaPlugins());
    }

    /**
     * @param \Popo\Builder\PluginContainerInterface $pluginContainer
     * @param \Popo\Schema\Reader\PropertyExplorerInterface $propertyExplorer
     *
     * @return \Popo\Plugin\Generator\GeneratorPluginInterface[]
     */
    protected function buildPropertyPluginCollection(
        PluginContainerInterface $pluginContainer,
        PropertyExplorerInterface $propertyExplorer
    ): array {
        $propertyPlugins = $this
            ->createPropertyFactoryPlugin($propertyExplorer)
            ->createPluginCollection();

        return \array_merge($propertyPlugins, $pluginContainer->getPropertyPlugins());
    }

    /**
     * @param \Popo\Builder\PluginContainerInterface $pluginContainer
     * @param \Popo\Schema\Reader\PropertyExplorerInterface $propertyExplorer
     *
     * @return \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected function buildArrayablePluginCollection(
        PluginContainerInterface $pluginContainer,
        PropertyExplorerInterface $propertyExplorer
    ): array {
        $arrayablePlugins = $this
            ->createArrayableFactoryPlugin($propertyExplorer)
            ->createPluginCollection();

        return \array_merge($arrayablePlugins, $pluginContainer->getArrayablePlugins());
    }

    /**
     * @param \Popo\Builder\PluginContainerInterface $pluginContainer
     * @param \Popo\Schema\Reader\PropertyExplorerInterface $propertyExplorer
     *
     * @return \Popo\Plugin\Generator\GeneratorPluginInterface[]
     */
    protected function buildCollectionPluginCollection(
        PluginContainerInterface $pluginContainer,
        PropertyExplorerInterface $propertyExplorer
    ): array {
        $collectionPlugins = $this
            ->createCollectionFactoryPlugin($propertyExplorer)
            ->createPluginCollection();

        return \array_merge($collectionPlugins, $pluginContainer->getCollectionPlugins());
    }
}
