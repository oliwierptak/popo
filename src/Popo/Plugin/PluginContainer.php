<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use LogicException;
use Popo\PopoConfigurator;

class PluginContainer implements PluginContainerInterface
{
    protected PopoConfigurator $configurator;

    public function __construct(PopoConfigurator $configurator)
    {
        $this->configurator = $configurator;
    }

    /**
     * @return array<\Popo\Plugin\PhpFilePluginInterface>
     */
    public function createPhpFilePlugin(): array
    {
        return $this->createPluginCollection($this->configurator->getPhpFilePluginCollection());
    }

    /**
     * @return array<\Popo\Plugin\NamespacePluginInterface>
     */
    public function createNamespacePlugin(): array
    {
        return $this->createPluginCollection($this->configurator->getNamespacePluginCollection());
    }

    /**
     * @return array<\Popo\Plugin\ClassPluginInterface>
     */
    public function createClassPlugins(): array
    {
        return $this->createPluginCollection($this->configurator->getClassPluginCollection());
    }

    /**
     * @return array<\Popo\Plugin\PropertyPluginInterface>
     */
    public function createPropertyPlugins(): array
    {
        return $this->createPluginCollection($this->configurator->getPropertyPluginCollection());
    }

    public function createMappingPolicyPlugins(): array
    {
        return $this->createPluginCollection($this->configurator->getMappingPolicyPluginCollection());
    }

    /**
     * @param array<string> $pluginClassNames
     *
     * @phpstan-ignore-next-line
     * @return array
     */
    protected function createPluginCollection(array $pluginClassNames): array
    {
        $result = [];
        foreach ($pluginClassNames as $index => $pluginClassName) {
            if (!class_exists($pluginClassName)) {
                throw new LogicException('Invalid plugin class name: ' . $pluginClassName);
            }

            $result[$index] = new $pluginClassName();
        }

        return $result;
    }
}
