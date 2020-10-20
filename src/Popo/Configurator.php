<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Schema\SchemaConfigurator;
use Symfony\Component\Console\Output\OutputInterface;

class Configurator
{
    protected string $configName;

    protected string $schemaDirectory;

    protected string $templateDirectory;

    protected string $outputDirectory;

    protected string $namespace = '\\';

    protected string $extension = '.php';

    /**
     * @var bool|null if set, will overwrite the abstract value from schema file
     */
    protected $isAbstract = null;

    /**
     * @var bool|null if set, will overwrite the withInterface value from schema file
     */
    protected $withInterface = null;

    /**
     * @var string|null if set, the generated classes will be extended with this class
     */
    protected $extends = null;

    protected SchemaConfigurator $schemaConfigurator;

    protected string $returnType = 'self';

    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]|string[]
     */
    protected array $schemaPluginClasses = [];

    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]|string[]
     */
    protected array $arrayablePluginClasses = [];

    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]|string[]
     */
    protected array $propertyPluginClasses = [];

    /**
     * @var \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]|string[]
     */
    protected array $collectionPluginClasses = [];

    protected ?OutputInterface $output;

    public function __construct()
    {
        $this->schemaConfigurator = new SchemaConfigurator();
    }

    public function getConfigName(): string
    {
        return $this->configName;
    }

    public function setConfigName(string $configName): self
    {
        $this->configName = $configName;
        return $this;
    }

    public function getSchemaDirectory(): string
    {
        return $this->schemaDirectory;
    }

    public function setSchemaDirectory(string $schemaDirectory): self
    {
        $this->schemaDirectory = $schemaDirectory;

        return $this;
    }

    public function getTemplateDirectory(): string
    {
        return $this->templateDirectory;
    }

    public function setTemplateDirectory(string $templateDirectory): self
    {
        $this->templateDirectory = $templateDirectory;

        return $this;
    }

    public function getOutputDirectory(): string
    {
        return $this->outputDirectory;
    }

    public function setOutputDirectory(string $outputDirectory): self
    {
        $this->outputDirectory = $outputDirectory;

        return $this;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getIsAbstract(): ?bool
    {
        return $this->isAbstract;
    }

    public function setIsAbstract(?bool $isAbstract): self
    {
        $this->isAbstract = $isAbstract;

        return $this;
    }

    public function getWithInterface(): ?bool
    {
        return $this->withInterface;
    }

    public function setWithInterface(?bool $withInterface): self
    {
        $this->withInterface = $withInterface;

        return $this;
    }

    public function getExtends(): ?string
    {
        return $this->extends;
    }

    public function setExtends(?string $extends): self
    {
        $this->extends = $extends;

        return $this;
    }

    public function getReturnType(): string
    {
        return $this->returnType;
    }

    public function setReturnType(string $returnType): self
    {
        $this->returnType = $returnType;

        return $this;
    }

    public function getSchemaConfigurator(): SchemaConfigurator
    {
        return $this->schemaConfigurator;
    }

    public function setSchemaConfigurator(SchemaConfigurator $schemaConfigurator): self
    {
        $this->schemaConfigurator = $schemaConfigurator;

        return $this;
    }

    public function getSchemaPluginClasses(): array
    {
        return $this->schemaPluginClasses;
    }

    public function getArrayablePluginClasses(): array
    {
        return $this->arrayablePluginClasses;
    }

    /**
     * Format:
     *
     * [
     *  SchemaGeneratorPluginInterface::PATTERN => SchemaGeneratorPluginInterface::class,
     *  ]
     *
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]|string[] $schemaPluginClasses
     *
     * @return self
     */
    public function setArrayablePluginClasses(array $arrayablePluginClasses): self
    {
        $this->arrayablePluginClasses = $arrayablePluginClasses;

        return $this;
    }

    /**
     * Format:
     *
     * [
     *  SchemaGeneratorPluginInterface::PATTERN => SchemaGeneratorPluginInterface::class,
     *  ]
     *
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]|string[] $schemaPluginClasses
     *
     * @return self
     */
    public function setSchemaPluginClasses(array $schemaPluginClasses): self
    {
        $this->schemaPluginClasses = $schemaPluginClasses;

        return $this;
    }

    /**
     * @return Plugin\Generator\PropertyGeneratorPluginInterface[]|string[]
     */
    public function getPropertyPluginClasses(): array
    {
        return $this->propertyPluginClasses;
    }

    /**
     * Format:
     *
     * [
     *  PropertyGeneratorPluginInterface::PATTERN => PropertyGeneratorPluginInterface::class,
     *  ]
     *
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]|string[] $propertyPluginClasses
     *
     * @return self
     */
    public function setPropertyPluginClasses(array $propertyPluginClasses): self
    {
        $this->propertyPluginClasses = $propertyPluginClasses;

        return $this;
    }

    /**
     * @return Plugin\Generator\PropertyGeneratorPluginInterface[]|string[]
     */
    public function getCollectionPluginClasses(): array
    {
        return $this->collectionPluginClasses;
    }

    /**
     * Format of $propertyPlugins:
     *
     * [
     *  PropertyGeneratorPluginInterface::PATTERN => PropertyGeneratorPluginInterface::class,
     *  ]
     *
     * @param \Popo\Plugin\Generator\PropertyGeneratorPluginInterface[]|string[] $collectionPluginClasses
     *
     * @return self
     */
    public function setCollectionPluginClasses(array $collectionPluginClasses): self
    {
        $this->collectionPluginClasses = $collectionPluginClasses;

        return $this;
    }

    public function getOutput(): ?OutputInterface
    {
        return $this->output;
    }

    public function setOutput(?OutputInterface $output): self
    {
        $this->output = $output;

        return $this;
    }
}
