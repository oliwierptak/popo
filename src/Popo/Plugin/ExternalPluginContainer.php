<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use Popo\PopoConfigurator;

class ExternalPluginContainer
{
    /**
     * @var array<string>
     */
    protected static array $classPluginClassNames = [];

    /**
     * @var array<string>
     */
    protected static array $mappingPolicyPluginClassNames = [];

    /**
     * @var array<string>
     */
    protected static array $namespacePluginClassNames = [];

    /**
     * @var array<string>
     */
    protected static array $phpFilePluginClassNames = [];

    /**
     * @var array<string>
     */
    protected static array $propertyPluginClassNames = [];

    public function addClassPluginClassName(string $classPluginClassName): self
    {
        static::$classPluginClassNames[] = $classPluginClassName;

        return $this;
    }

    public function addMappingPolicyPluginClassName(string $mappingPolicyPluginClassName): self
    {
        static::$mappingPolicyPluginClassNames[] = $mappingPolicyPluginClassName;

        return $this;
    }

    public function addNamespacePluginClassName(string $namespacePluginClassName): self
    {
        static::$namespacePluginClassNames[] = $namespacePluginClassName;

        return $this;
    }

    public function addPhpFlePluginClassName(string $phpFilePluginClassName): self
    {
        static::$phpFilePluginClassNames[] = $phpFilePluginClassName;

        return $this;
    }

    public function addPropertyPluginClassName(string $propertyPluginClassName): self
    {
        static::$propertyPluginClassNames[] = $propertyPluginClassName;

        return $this;
    }

    public function reconfigure(PopoConfigurator $configurator): PopoConfigurator
    {
        foreach (static::$classPluginClassNames as $pluginClassName) {
            $configurator->addClassPluginClass($pluginClassName);
        }

        foreach (static::$mappingPolicyPluginClassNames as $pluginClassName) {
            $configurator->addMappingPolicyPluginClass($pluginClassName);
        }

        foreach (static::$namespacePluginClassNames as $pluginClassName) {
            $configurator->addNamespacePluginClass($pluginClassName);
        }

        foreach (static::$phpFilePluginClassNames as $pluginClassName) {
            $configurator->addPhpFilePluginClass($pluginClassName);
        }

        foreach (static::$propertyPluginClassNames as $pluginClassName) {
            $configurator->addPropertyPluginClass($pluginClassName);
        }

        return $configurator;
    }
}
