<?php

declare(strict_types = 1);

namespace Popo\Plugin;

interface PluginContainerInterface
{
    /**
     * Plugins responsible for generating header of PHP file
     *
     * Specification:
     * - Iterate over php file plugin collection
     * - Create specified plugin classes
     * - Throw exception in case plugin class could not be found
     * - Return collection of classes implementing \Popo\Plugin\PhpFilePluginInterface
     *
     * @param array<string> $collection Extra class plugins to use
     *
     * @return array<\Popo\Plugin\PhpFilePluginInterface>
     * @throws \LogicException In case plugin class could not be found
     */
    public function createPhpFilePlugin(array $collection = []): array;

    /**
     * Plugins responsible for generating namespace section
     *
     * Specification:
     * - Iterate over namespace plugin collection
     * - Create specified plugin classes
     * - Throw exception in case plugin class could not be found
     * - Return collection of classes implementing \Popo\Plugin\NamespacePluginInterface
     *
     * @param array<string> $collection Extra class plugins to use
     *
     * @return array<\Popo\Plugin\NamespacePluginInterface>
     * @throws \LogicException In case plugin class could not be found
     */
    public function createNamespacePlugin(array $collection = []): array;

    /**
     * Plugins responsible for generating various methods
     *
     * Specification:
     * - Merge default collection with collection parameter
     * - Iterate over class plugin collection
     * - Create specified plugin classes
     * - Throw exception in case plugin class could not be found
     * - Return collection of classes implementing \Popo\Plugin\ClassPluginInterface
     *
     * @param array<string> $collection Extra class plugins to use
     *
     * @return array<\Popo\Plugin\ClassPluginInterface>
     * @throws \LogicException In case plugin class could not be found
     */
    public function createClassPlugins(array $collection = []): array;

    /**
     * Plugins responsible for generating property related methods only
     *
     * Specification:
     * - Iterate over property plugin collection
     * - Create specified plugin classes
     * - Throw exception in case plugin class could not be found
     * - Return collection of classes implementing \Popo\Plugin\PropertyPluginInterface
     *
     * @param array<string> $collection Extra class plugins to use
     *
     * @return array<\Popo\Plugin\PropertyPluginInterface>
     * @throws \LogicException In case plugin class could not be found
     */
    public function createPropertyPlugins(array $collection = []): array;

    /**
     * Plugins responsible for transforming schema key names
     *
     * Specification:
     * - Iterate over mapping policy plugin collection
     * - Create specified plugin classes
     * - Throw exception in case plugin class could not be found
     * - Return collection of classes implementing \Popo\Plugin\MappingPolicyPluginInterface
     *
     * @param array<string> $collection Extra class plugins to use
     *
     * @return array<\Popo\Plugin\MappingPolicyPluginInterface>
     * @throws \LogicException In case plugin class could not be found
     */
    public function createMappingPolicyPlugins(array $collection = []): array;
}
