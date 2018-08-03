<?php

declare(strict_types = 1);

namespace Popo\Builder;

interface PluginContainerInterface
{
    /**
     * Specification:
     * - Requires list of classes implementing \Popo\Plugin\Generator\PropertyGeneratorPluginInterface.
     * - Creates instances of plugin classes.
     * - Plugin class won't be instantiated when it does not implement PropertyGeneratorPluginInterface.
     *
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[] $pluginCollection
     *
     * @return \Popo\Builder\PluginContainerInterface
     */
    public function registerPropertyClassPlugins(array $pluginCollection): PluginContainerInterface;

    /**
     * Specification:
     * - Requires list of classes implementing \Popo\Plugin\Generator\PropertyGeneratorPluginInterface.
     * - Creates instances of plugin classes.
     * - Plugin class won't be instantiated when it does not implement PropertyGeneratorPluginInterface.
     *
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[] $pluginCollection
     *
     * @return \Popo\Builder\PluginContainerInterface
     */
    public function registerCollectionClassPlugins(array $pluginCollection): PluginContainerInterface;

    /**
     * Specification:
     * - Requires list of classes implementing \Popo\Plugin\Generator\SchemaGeneratorPluginInterface.
     * - Creates instances of plugin classes.
     * - Plugin class won't be instantiated when it does not implement SchemaGeneratorPluginInterface.
     *
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[] $pluginCollection
     *
     * @return \Popo\Builder\PluginContainerInterface
     */
    public function registerSchemaClassPlugins(array $pluginCollection): PluginContainerInterface;

    /**
     * Specification:
     * - Returns plugin classes instantiated with registerPropertyClassPlugins()
     *
     * @return \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     *
     * @see PluginContainerInterface::registerPropertyClassPlugins()
     */
    public function getPropertyPlugins(): array;

    /**
     * Specification:
     * - Returns plugin classes instantiated with registerCollectionClassPlugins()
     *
     * @return \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     *
     * @see PluginContainerInterface::registerPropertyClassPlugins()
     */
    public function getCollectionPlugins(): array;

    /**
     * Specification:
     * - Returns plugin classes instantiated with registerSchemaClassPlugins()
     *
     * @return \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     *
     * @see PluginContainerInterface::registerSchemaClassPlugins()
     */
    public function getSchemaPlugins(): array;
}
