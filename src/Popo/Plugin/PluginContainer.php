<?php declare(strict_types = 1);

namespace Popo\Plugin;

use Popo\Plugin\Generator\PropertyGeneratorPluginInterface;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyExplorer;

class PluginContainer
{
    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected array $schemaPlugins = [];
    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected array $arrayablePlugins = [];
    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected array $propertyPlugins = [];
    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected array $collectionPlugins = [];
    protected PropertyExplorer $propertyExplorer;

    public function __construct(PropertyExplorer $propertyExplorer)
    {
        $this->propertyExplorer = $propertyExplorer;
    }

    public function registerPropertyClassPlugins(array $pluginCollection): PluginContainer
    {
        foreach ($pluginCollection as $pattern => $pluginClass) {
            $plugin = new $pluginClass(
                $this->propertyExplorer
            );

            if (!($plugin instanceof PropertyGeneratorPluginInterface)) {
                continue;
            }

            $this->propertyPlugins[$pattern] = $plugin;
        }

        return $this;
    }

    public function registerCollectionClassPlugins(array $pluginCollection): PluginContainer
    {
        foreach ($pluginCollection as $pattern => $pluginClass) {
            $plugin = new $pluginClass(
                $this->propertyExplorer
            );

            if (!($plugin instanceof PropertyGeneratorPluginInterface)) {
                continue;
            }

            $this->collectionPlugins[$pattern] = $plugin;
        }

        return $this;
    }

    public function registerSchemaClassPlugins(array $pluginCollection): PluginContainer
    {
        foreach ($pluginCollection as $pattern => $pluginClass) {
            $plugin = new $pluginClass(
                $this->propertyExplorer
            );

            if (!($plugin instanceof SchemaGeneratorPluginInterface)) {
                continue;
            }

            $this->schemaPlugins[$pattern] = $plugin;
        }

        return $this;
    }

    public function registerArrayableClassPlugins(array $pluginCollection): PluginContainer
    {
        foreach ($pluginCollection as $pattern => $pluginClass) {
            $plugin = new $pluginClass(
                $this->propertyExplorer
            );

            if (!($plugin instanceof SchemaGeneratorPluginInterface)) {
                continue;
            }

            $this->arrayablePlugins[$pattern] = $plugin;
        }

        return $this;
    }

    public function getPropertyPlugins(): array
    {
        return $this->propertyPlugins;
    }

    public function getSchemaPlugins(): array
    {
        return $this->schemaPlugins;
    }

    public function getCollectionPlugins(): array
    {
        return $this->collectionPlugins;
    }

    public function getArrayablePlugins(): array
    {
        return $this->arrayablePlugins;
    }
}
