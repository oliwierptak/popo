<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use LogicException;
use Nette\PhpGenerator\Literal;
use Popo\PopoConfigurator;

class PluginContainer implements PluginContainerInterface
{
    protected PopoConfigurator $configurator;

    public function __construct(PopoConfigurator $configurator)
    {
        $this->configurator = $configurator;
    }

    public function createPhpFilePlugin(array $collection = []): array
    {
        $pluginCollection = $this->normalizePluginClassNames($collection);

        return $this->createPluginCollection(
            array_merge(
                $this->configurator->getPhpFilePluginCollection(),
                $pluginCollection,
            ),
        );
    }

    public function createNamespacePlugin(array $collection = []): array
    {
        $pluginCollection = $this->normalizePluginClassNames($collection);

        return $this->createPluginCollection(
            array_merge(
                $this->configurator->getNamespacePluginCollection(),
                $pluginCollection,
            ),
        );
    }

    public function createClassPlugins(array $collection = []): array
    {
        $pluginCollection = $this->normalizePluginClassNames($collection);

        return $this->createPluginCollection(
            array_merge(
                $this->configurator->getClassPluginCollection(),
                $pluginCollection,
            ),
        );
    }

    public function createPropertyPlugins(array $collection = []): array
    {
        $pluginCollection = $this->normalizePluginClassNames($collection);

        return $this->createPluginCollection(
            array_merge(
                $this->configurator->getPropertyPluginCollection(),
                $pluginCollection,
            ),
        );
    }

    public function createMappingPolicyPlugins(array $collection = []): array
    {
        $pluginCollection = $this->normalizePluginClassNames($collection);

        return $this->createPluginCollection(
            array_merge(
                $this->configurator->getMappingPolicyPluginCollection(),
                $pluginCollection,
            ),
        );
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

    /**
     * @param array<string> $collection
     *
     * @return array<string>
     */
    public function normalizePluginClassNames(array $collection): array
    {
        return array_map(function (string $pluginClassName) {
            $pluginClassName = str_replace('::class', '', $pluginClassName);
            return (string)new Literal($pluginClassName);
        }, $collection);
    }
}
