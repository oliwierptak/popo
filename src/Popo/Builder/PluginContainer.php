<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Plugin\Generator\GeneratorPluginInterface;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyExplorerInterface;

class PluginContainer implements PluginContainerInterface
{
    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected $schemaPlugins = [];

    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected $arrayablePlugins = [];

    /**
     * @var \Popo\Plugin\Generator\GeneratorPluginInterface[]
     */
    protected $propertyPlugins = [];

    /**
     * @var \Popo\Plugin\Generator\GeneratorPluginInterface[]
     */
    protected $collectionPlugins = [];

    /**
     * @var \Popo\Schema\Reader\PropertyExplorerInterface
     */
    protected $propertyExplorer;

    public function __construct(PropertyExplorerInterface $propertyExplorer)
    {
        $this->propertyExplorer = $propertyExplorer;
    }

    public function registerPropertyClassPlugins(array $pluginCollection): PluginContainerInterface
    {
        foreach ($pluginCollection as $pattern => $pluginClass) {
            $plugin = new $pluginClass(
                $this->propertyExplorer
            );

            if (!($plugin instanceof GeneratorPluginInterface)) {
                continue;
            }

            $this->propertyPlugins[$pattern] = $plugin;
        }

        return $this;
    }

    public function registerCollectionClassPlugins(array $pluginCollection): PluginContainerInterface
    {
        foreach ($pluginCollection as $pattern => $pluginClass) {
            $plugin = new $pluginClass(
                $this->propertyExplorer
            );

            if (!($plugin instanceof GeneratorPluginInterface)) {
                continue;
            }

            $this->collectionPlugins[$pattern] = $plugin;
        }

        return $this;
    }

    public function registerSchemaClassPlugins(array $pluginCollection): PluginContainerInterface
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

    public function registerArrayableClassPlugins(array $pluginCollection): PluginContainerInterface
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
