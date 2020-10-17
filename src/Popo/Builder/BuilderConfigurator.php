<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Popo\Schema\SchemaConfigurator;
use Popo\Schema\SchemaConfiguratorInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuilderConfigurator
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

    /**
     * @var \Popo\Schema\SchemaConfiguratorInterface
     */
    protected $schemaConfigurator;

    /**
     * @var string
     */
    protected $returnType = 'self';

    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected $schemaPluginClasses = [];

    /**
     * @var \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    protected $arrayablePluginClasses = [];

    /**
     * @var \Popo\Plugin\Generator\GeneratorPluginInterface[]
     */
    protected $propertyPluginClasses = [];

    /**
     * @var \Popo\Plugin\Generator\GeneratorPluginInterface[]
     */
    protected $collectionPluginClasses = [];

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct()
    {
        $this->schemaConfigurator = new SchemaConfigurator();
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

    public function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->schemaConfigurator;
    }

    public function setSchemaConfigurator(SchemaConfiguratorInterface $schemaConfigurator): self
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
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[] $schemaPluginClasses
     *
     * @return \Popo\Builder\BuilderConfigurator
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
     * @param \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[] $schemaPluginClasses
     *
     * @return \Popo\Builder\BuilderConfigurator
     */
    public function setSchemaPluginClasses(array $schemaPluginClasses): self
    {
        $this->schemaPluginClasses = $schemaPluginClasses;

        return $this;
    }

    public function getPropertyPluginClasses(): array
    {
        return $this->propertyPluginClasses;
    }

    /**
     * Format:
     *
     * [
     *  GeneratorPluginInterface::PATTERN => GeneratorPluginInterface::class,
     *  ]
     *
     * @param \Popo\Plugin\Generator\GeneratorPluginInterface[] $propertyPluginClasses
     *
     * @return \Popo\Builder\BuilderConfigurator
     */
    public function setPropertyPluginClasses(array $propertyPluginClasses): self
    {
        $this->propertyPluginClasses = $propertyPluginClasses;

        return $this;
    }

    public function getCollectionPluginClasses(): array
    {
        return $this->collectionPluginClasses;
    }

    /**
     * Format of $propertyPlugins:
     *
     * [
     *  GeneratorPluginInterface::PATTERN => GeneratorPluginInterface::class,
     *  ]
     *
     * @param \Popo\Plugin\Generator\GeneratorPluginInterface[] $collectionPluginClasses
     *
     * @return \Popo\Builder\BuilderConfigurator
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
