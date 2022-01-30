<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use LogicException;
use Popo\PopoConfigurator;

class PluginContainer implements PluginContainerInterface
{
    public function createClassPlugins(PopoConfigurator $configurator): array
    {
        return $this->createPluginCollection($configurator->getClassPluginCollection());
    }

    public function createPropertyPlugins(PopoConfigurator $configurator): array
    {
        return $this->createPluginCollection($configurator->getPropertyPluginCollection());
    }

    protected function createPluginCollection(array $pluginClassNames): array
    {
        $result = [];
        foreach ($pluginClassNames as $pluginClassName) {
            if (!class_exists($pluginClassName)) {
                throw new LogicException('Invalid plugin class name: ' . $pluginClassName);
            }

            $result[] = new $pluginClassName();
        }

        return $result;
    }
}
