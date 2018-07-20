<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Schema\SchemaConfigurator;
use Popo\Schema\SchemaConfiguratorInterface;

class BuilderConfigurator implements BuilderConfiguratorInterface
{
    /**
     * @var string
     */
    protected $schemaDirectory;

    /**
     * @var string
     */
    protected $templateDirectory;

    /**
     * @var string
     */
    protected $outputDirectory;

    /**
     * @var string
     */
    protected $namespace = '\\';

    /**
     * @var string
     */
    protected $extension = '.php';

    /**
     * @var \Popo\Schema\SchemaConfiguratorInterface
     */
    protected $schemaConfigurator;

    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected $schemaPluginClasses = [];

    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]
     */
    protected $propertyPluginClasses = [];

    public function __construct()
    {
        $this->schemaConfigurator = new SchemaConfigurator();
    }

    public function getSchemaDirectory(): string
    {
        return $this->schemaDirectory;
    }

    public function setSchemaDirectory(string $schemaDirectory): BuilderConfiguratorInterface
    {
        $this->schemaDirectory = $schemaDirectory;

        return $this;
    }

    public function getTemplateDirectory(): string
    {
        return $this->templateDirectory;
    }

    public function setTemplateDirectory(string $templateDirectory): BuilderConfiguratorInterface
    {
        $this->templateDirectory = $templateDirectory;

        return $this;
    }

    public function getOutputDirectory(): string
    {
        return $this->outputDirectory;
    }

    public function setOutputDirectory(string $outputDirectory): BuilderConfiguratorInterface
    {
        $this->outputDirectory = $outputDirectory;

        return $this;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): BuilderConfiguratorInterface
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): BuilderConfiguratorInterface
    {
        $this->extension = $extension;

        return $this;
    }

    public function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->schemaConfigurator;
    }

    public function setSchemaConfigurator(SchemaConfiguratorInterface $schemaConfigurator): BuilderConfiguratorInterface
    {
        $this->schemaConfigurator = $schemaConfigurator;

        return $this;
    }

    public function getSchemaPluginClasses(): array
    {
        return $this->schemaPluginClasses;
    }

    public function setSchemaPluginClasses(array $schemaPluginClasses): BuilderConfiguratorInterface
    {
        $this->schemaPluginClasses = $schemaPluginClasses;

        return $this;
    }

    public function getPropertyPluginClasses(): array
    {
        return $this->propertyPluginClasses;
    }

    public function setPropertyPluginClasses(array $propertyPluginClasses): BuilderConfiguratorInterface
    {
        $this->propertyPluginClasses = $propertyPluginClasses;

        return $this;
    }
}
