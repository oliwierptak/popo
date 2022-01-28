<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use Popo\PopoConfigurator;

interface PluginContainerInterface
{
    /**
     * Specification:
     * - Iterate over class plugin collection
     * - Create specified plugin class
     * - Throw exception in case plugin class could not be found
     * - Return collection of classes implementing \Popo\Plugin\ClassPluginInterface
     *
     * @param \Popo\PopoConfigurator $configurator
     *
     * @return \Popo\Plugin\ClassPluginInterface[]
     * @throws \LogicException In case plugin class could not be found
     */
    public function createClassPlugins(PopoConfigurator $configurator): array;

    /**
     * Specification:
     * - Iterate over class plugin collection
     * - Create specified plugin class
     * - Throw exception in case plugin class could not be found
     * - Return collection of classes implementing \Popo\Plugin\PropertyPluginInterface
     *
     * @param \Popo\PopoConfigurator $configurator
     *
     * @return \Popo\Plugin\PropertyPluginInterface[]
     * @throws \LogicException In case plugin class could not be found
     */
    public function createPropertyPlugins(PopoConfigurator $configurator): array;
}
