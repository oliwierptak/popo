<?php declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Configurator;
use Popo\Generator\GeneratorFactory;
use Popo\Generator\Php\Plugin\ArrayableFactoryPlugin;
use Popo\Generator\Php\Plugin\CollectionFactoryPlugin;
use Popo\Generator\Php\Plugin\PropertyFactoryPlugin;
use Popo\Generator\Php\Plugin\SchemaFactoryPlugin;
use Popo\Generator\SchemaGenerator;
use Popo\Plugin\Factory\PropertyFactoryPluginInterface;
use Popo\Plugin\Factory\SchemaFactoryPluginInterface;
use Popo\Plugin\PluginContainer;
use Popo\Schema\Loader\ContentLoader;
use Popo\Schema\Reader\PropertyExplorer;
use Popo\Schema\SchemaFactory;
use SplFileInfo;
use function array_merge;

class PopoGeneratorBuilder
{
    protected ContentLoader $contentLoader;
    protected GeneratorFactory $generatorFactory;
    protected SchemaFactory $schemaFactory;

    public function __construct(
        ContentLoader $contentLoader,
        GeneratorFactory $generatorFactory,
        SchemaFactory $schemaFactory
    ) {
        $this->contentLoader = $contentLoader;
        $this->generatorFactory = $generatorFactory;
        $this->schemaFactory = $schemaFactory;
    }

    public function build(Configurator $configurator, PluginContainer $pluginContainer): SchemaGenerator
    {
        $container = $this->buildContainer($configurator, $pluginContainer);
        $schemaGenerator = $this->generatorFactory->createSchemaGenerator($container);

        return $schemaGenerator;
    }

    protected function buildContainer(
        Configurator $configurator,
        PluginContainer $pluginContainer
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
        $filename = new SplFileInfo($templateDirectory . $templateFilename);
        $schemaTemplateString = $this->contentLoader->load($filename);

        return $schemaTemplateString;
    }

    /**
     * @param \Popo\Plugin\PluginContainer $pluginContainer
     * @param \Popo\Schema\Reader\PropertyExplorer $propertyExplorer
     *
     * @return \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected function buildSchemaPluginCollection(
        PluginContainer $pluginContainer,
        PropertyExplorer $propertyExplorer
    ): array {
        $schemaPlugins = $this
            ->createSchemaFactoryPlugin($propertyExplorer)
            ->createPluginCollection();

        return array_merge($schemaPlugins, $pluginContainer->getSchemaPlugins());
    }

    protected function createSchemaFactoryPlugin(PropertyExplorer $propertyExplorer): SchemaFactoryPluginInterface
    {
        return new SchemaFactoryPlugin($propertyExplorer);
    }

    /**
     * @param \Popo\Plugin\PluginContainer $pluginContainer
     * @param \Popo\Schema\Reader\PropertyExplorer $propertyExplorer
     *
     * @return \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected function buildPropertyPluginCollection(
        PluginContainer $pluginContainer,
        PropertyExplorer $propertyExplorer
    ): array {
        $propertyPlugins = $this
            ->createPropertyFactoryPlugin($propertyExplorer)
            ->createPluginCollection();

        return array_merge($propertyPlugins, $pluginContainer->getPropertyPlugins());
    }

    protected function createPropertyFactoryPlugin(PropertyExplorer $propertyExplorer): PropertyFactoryPluginInterface
    {
        return new PropertyFactoryPlugin($propertyExplorer);
    }

    /**
     * @param \Popo\Plugin\PluginContainer $pluginContainer
     * @param \Popo\Schema\Reader\PropertyExplorer $propertyExplorer
     *
     * @return \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected function buildArrayablePluginCollection(
        PluginContainer $pluginContainer,
        PropertyExplorer $propertyExplorer
    ): array {
        $arrayablePlugins = $this
            ->createArrayableFactoryPlugin($propertyExplorer)
            ->createPluginCollection();

        return array_merge($arrayablePlugins, $pluginContainer->getArrayablePlugins());
    }

    protected function createArrayableFactoryPlugin(PropertyExplorer $propertyExplorer): SchemaFactoryPluginInterface
    {
        return new ArrayableFactoryPlugin($propertyExplorer);
    }

    /**
     * @param \Popo\Plugin\PluginContainer $pluginContainer
     * @param \Popo\Schema\Reader\PropertyExplorer $propertyExplorer
     *
     * @return \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected function buildCollectionPluginCollection(
        PluginContainer $pluginContainer,
        PropertyExplorer $propertyExplorer
    ): array {
        $collectionPlugins = $this
            ->createCollectionFactoryPlugin($propertyExplorer)
            ->createPluginCollection();

        return array_merge($collectionPlugins, $pluginContainer->getCollectionPlugins());
    }

    protected function createCollectionFactoryPlugin(PropertyExplorer $propertyExplorer): PropertyFactoryPluginInterface
    {
        return new CollectionFactoryPlugin($propertyExplorer);
    }
}
