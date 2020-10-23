<?php declare(strict_types = 1);

namespace Popo;

use Popo\Model\Helper\ModelHelperConfigurator;
use Popo\Schema\SchemaConfigurator;

class Configurator
{
    protected string $configName;

    protected string $schemaDirectory;

    protected string $templateDirectory;

    protected string $outputDirectory;

    protected string $namespace = '\\';

    protected string $extension = '.php';

    /**
     * Value to be used for interfaces
     */
    protected string $namespaceWithInterface = '';

    /**
     * Determines if generated POPO will be abstract class
     */
    protected bool $isAbstract = false;

    /**
     * Only generate POPO files
     */
    protected bool $withPopo = true;

    /**
     * Only generate interfaces
     */
    protected bool $withInterface = false;

    /**
     * Generated class will be extended by this value
     */
    protected string $extends = '';

    /**
     * The return type of fromArray() method will be set to this value
     */
    protected string $returnType = 'self';

    protected SchemaConfigurator $schemaConfigurator;

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

    protected ModelHelperConfigurator $modelHelperConfigurator;

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

    public function getNamespaceWithInterface(): ?string
    {
        return $this->namespaceWithInterface;
    }

    public function setNamespaceWithInterface(string $namespaceWithInterface): self
    {
        $this->namespaceWithInterface = $namespaceWithInterface;

        return $this;
    }

    public function getIsAbstract(): ?bool
    {
        return $this->isAbstract;
    }

    public function setIsAbstract(bool $isAbstract): self
    {
        $this->isAbstract = $isAbstract;

        return $this;
    }

    public function getWithPopo(): ?bool
    {
        return $this->withPopo;
    }

    public function setWithPopo(bool $withPopo): self
    {
        $this->withPopo = $withPopo;

        return $this;
    }

    public function getWithInterface(): ?bool
    {
        return $this->withInterface;
    }

    public function setWithInterface(bool $withInterface): self
    {
        $this->withInterface = $withInterface;

        return $this;
    }

    public function getExtends(): ?string
    {
        return $this->extends;
    }

    public function setExtends(string $extends): self
    {
        $this->extends = $extends;

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

    public function getReturnType(): ?string
    {
        return $this->returnType;
    }

    public function setReturnType(string $returnType): self
    {
        $this->returnType = $returnType;

        return $this;
    }

    public function getSchemaPluginClasses()
    {
        return $this->schemaPluginClasses;
    }

    public function setSchemaPluginClasses($schemaPluginClasses)
    {
        $this->schemaPluginClasses = $schemaPluginClasses;

        return $this;
    }

    public function getArrayablePluginClasses()
    {
        return $this->arrayablePluginClasses;
    }

    public function setArrayablePluginClasses($arrayablePluginClasses)
    {
        $this->arrayablePluginClasses = $arrayablePluginClasses;

        return $this;
    }

    public function getPropertyPluginClasses()
    {
        return $this->propertyPluginClasses;
    }

    public function setPropertyPluginClasses($propertyPluginClasses)
    {
        $this->propertyPluginClasses = $propertyPluginClasses;

        return $this;
    }

    public function getCollectionPluginClasses()
    {
        return $this->collectionPluginClasses;
    }

    public function setCollectionPluginClasses($collectionPluginClasses)
    {
        $this->collectionPluginClasses = $collectionPluginClasses;

        return $this;
    }

    public function getModelHelperConfigurator(): ModelHelperConfigurator
    {
        if (empty($this->modelHelperConfigurator)) {
            $this->modelHelperConfigurator = new ModelHelperConfigurator();
        }

        return $this->modelHelperConfigurator;
    }

    public function setModelHelperConfigurator(ModelHelperConfigurator $modelHelperConfigurator): self
    {
        $this->modelHelperConfigurator = $modelHelperConfigurator;

        return $this;
    }
}
