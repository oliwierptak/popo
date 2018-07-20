<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Generator\GeneratorFactoryInterface;
use Popo\Generator\GeneratorInterface;
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

    public function __construct(ContentLoaderInterface $contentLoader, GeneratorFactoryInterface $generatorFactory, SchemaFactoryInterface $schemaFactory)
    {
        $this->contentLoader = $contentLoader;
        $this->generatorFactory = $generatorFactory;
        $this->schemaFactory = $schemaFactory;
    }

    public function build(BuilderConfiguratorInterface $configurator, PluginContainerInterface $pluginContainer): GeneratorInterface
    {
        $propertyExplorer = $this->schemaFactory
            ->createPropertyExplorer();

        $templateDirectory = $configurator->getTemplateDirectory();
        $schemaTemplateFilename = $configurator->getSchemaConfigurator()->getSchemaTemplateFilename();
        $propertyTemplateFilename = $configurator->getSchemaConfigurator()->getPropertyTemplateFilename();

        $schemaGenerator = $this->generatorFactory
            ->createSchemaGenerator(
                $this->getTemplateString($templateDirectory, $schemaTemplateFilename),
                $this->getTemplateString($templateDirectory, $propertyTemplateFilename),
                $this->buildSchemaPluginCollection($pluginContainer, $propertyExplorer),
                $this->buildPropertyPluginCollection($pluginContainer, $propertyExplorer)
            );

        return $schemaGenerator;
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
     * @return \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
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
}
