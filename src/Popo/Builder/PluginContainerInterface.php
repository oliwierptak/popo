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
     * @return void
     */
    public function registerPropertyClassPlugins(array $pluginCollection): void;

    /**
     * Specification:
     * - Requires list of classes implementing \Popo\Plugin\Generator\SchemaGeneratorPluginInterface.
     * - Creates instances of plugin classes.
     * - Plugin class won't be instantiated when it does not implement SchemaGeneratorPluginInterface.
     *
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[] $pluginCollection
     *
     * @return void
     */
    public function registerSchemaClassPlugins(array $pluginCollection): void;

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
     * - Returns plugin classes instantiated with registerSchemaClassPlugins()
     *
     * @return \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     *
     * @see PluginContainerInterface::registerSchemaClassPlugins()
     */
    public function getSchemaPlugins(): array;
}
