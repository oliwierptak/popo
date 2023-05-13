<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Model\Generate\GenerateResult;

interface PopoFacadeInterface
{
    /**
     * Specification:
     * - Validate, throw exception in case of error.
     * - Generate POPO files based on schema.
     * - Create target directories based on output path and namespace.
     * - Save POPO files under location based on output path and namespace.
     * - Return instance of GenerateResult
     *
     * @param \Popo\PopoConfigurator $configurator
     *
     * @return \Popo\Model\Generate\GenerateResult
     * @throws \InvalidArgumentException
     */
    public function generate(PopoConfigurator $configurator): GenerateResult;

    /**
     * Specification:
     * - Add class name of plugin implementing \Popo\Plugin\ClassPluginInterface
     *   This value is static and persist between Facade instantiations (monostate)
     *
     * @param string $classPluginClassName
     *
     * @return self
     */
    public function addClassPluginClassName(string $classPluginClassName): self;

    /**
     * Specification:
     * - Add class name of plugin implementing \Popo\Plugin\MappingPolicyPluginInterface
     *   This value is static and persist between Facade instantiations (monostate)
     *
     * @param string $mappingPolicyPluginClassName
     *
     * @return self
     */
    public function addMappingPolicyPluginClassName(string $mappingPolicyPluginClassName): self;

    /**
     * Specification:
     * - Add class name of plugin implementing \Popo\Plugin\NamespacePluginInterface
     *   This value is static and persist between Facade instantiations (monostate)
     *
     * @param string $namespacePluginClassName
     *
     * @return self
     */
    public function addNamespacePluginClassName(string $namespacePluginClassName): self;

    /**
     * Specification:
     * - Add class name of plugin implementing \Popo\Plugin\PhpFilePluginInterface
     *   This value is static and persist between Facade instantiations (monostate)
     *
     * @param string $phpFilePluginClassName
     *
     * @return self
     */
    public function addPhpFilePluginClassName(string $phpFilePluginClassName): self;

    /**
     * Specification:
     * - Add class name of plugin implementing \Popo\Plugin\PropertyPluginInterface.
     *   This value is static and persist between Facade instantiations (monostate)
     *
     * @param string $propertyPluginClassName
     *
     * @return self
     */
    public function addPropertyPluginClassName(string $propertyPluginClassName): self;

    /**
     * Specification:
     * - Add plugins to PopoConfigurator set with addClassPlugin()
     * - Add plugins to PopoConfigurator set with addMappingPolicyClassPlugin()
     * - Add plugins to PopoConfigurator set with addNamespacePluginClassName()
     * - Add plugins to PopoConfigurator set with addPhpFilePluginClassName()
     * - Add plugins to PopoConfigurator set with addPropertyPluginClassName()
     *
     * @param \Popo\PopoConfigurator $configurator
     *
     * @return \Popo\PopoConfigurator
     */
    public function reconfigure(PopoConfigurator $configurator): PopoConfigurator;
}
