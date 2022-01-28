<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use LogicException;
use Popo\PopoConfigurator;

class PluginContainer implements PluginContainerInterface
{
    public function createClassPlugins(PopoConfigurator $configurator): array
    {
        $result = [];
        foreach ($configurator->getClassPluginCollection() as $pluginClassName) {
            if (!class_exists($pluginClassName)) {
                throw new LogicException('Invalid plugin class name: ' . $pluginClassName);
            }

            $result[] = new $pluginClassName();
        }

        return $result;
    }

    public function createPropertyPlugins(PopoConfigurator $configurator): array
    {
        $result = [];
        foreach ($configurator->getPropertyPluginCollection() as $pluginClassName) {
            if (!class_exists($pluginClassName)) {
                throw new LogicException('Invalid plugin class name: ' . $pluginClassName);
            }

            $result[] = new $pluginClassName();
        }

        return $result;
    }
}
