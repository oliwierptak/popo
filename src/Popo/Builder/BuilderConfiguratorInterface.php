<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Schema\SchemaConfiguratorInterface;

interface BuilderConfiguratorInterface
{
    public function getSchemaDirectory(): string;

    public function setSchemaDirectory(string $schemaDirectory): BuilderConfiguratorInterface;

    public function getTemplateDirectory(): string;

    public function setTemplateDirectory(string $templateDirectory): BuilderConfiguratorInterface;

    public function getOutputDirectory(): string;

    public function setOutputDirectory(string $outputDirectory): BuilderConfiguratorInterface;

    public function getNamespace(): string;

    public function setNamespace(string $namespace): BuilderConfiguratorInterface;

    public function getExtension(): string;

    public function setExtension(string $extension): BuilderConfiguratorInterface;

    public function getSchemaConfigurator(): SchemaConfiguratorInterface;

    public function setSchemaConfigurator(SchemaConfiguratorInterface $schemaBuilderConfigurator): BuilderConfiguratorInterface;

    public function getSchemaPluginClasses(): array;

    /**
     * Format of $schemaPlugins:
     *
     * [
     *  SchemaGeneratorPluginInterface::PATTERN => SchemaGeneratorPluginInterface::class,
     *  ]
     *
     * @param array $schemaPlugins
     *
     * @return \Popo\Builder\BuilderConfiguratorInterface
     */
    public function setSchemaPluginClasses(array $schemaPlugins): BuilderConfiguratorInterface;

    public function getPropertyPluginClasses(): array;

    /**
     * Format of $propertyPlugins:
     *
     * [
     *  PropertyGeneratorPluginInterface::PATTERN => PropertyGeneratorPluginInterface::class,
     *  ]
     *
     * @param array $propertyPlugins
     *
     * @return \Popo\Builder\BuilderConfiguratorInterface
     */
    public function setPropertyPluginClasses(array $propertyPlugins): BuilderConfiguratorInterface;
}
