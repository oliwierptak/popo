<?php

declare(strict_types = 1);

namespace Popo\Generator\Php\Plugin;

use Popo\Generator\Php\Plugin\Schema\AbstractClassGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\ClassNameGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\CollectionItemsGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\ExtendGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\NamespaceGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\PropertyMappingGeneratorPlugin;
use Popo\Generator\Php\Plugin\Schema\SchemaDataGeneratorPlugin;
use Popo\Plugin\Factory\SchemaFactoryPluginInterface;
use Popo\Plugin\Generator\SchemaGeneratorPluginInterface;
use Popo\Schema\Reader\PropertyExplorerInterface;

class SchemaFactoryPlugin implements SchemaFactoryPluginInterface
{
    /**
     * @var \Popo\Schema\Reader\PropertyExplorerInterface
     */
    protected $propertyExplorer;

    public function __construct(PropertyExplorerInterface $propertyExplorer)
    {
        $this->propertyExplorer = $propertyExplorer;
    }

    /**
     * @return \Popo\Plugin\Generator\SchemaGeneratorPluginInterface[]
     */
    public function createPluginCollection(): array
    {
        return [
            NamespaceGeneratorPlugin::PATTERN => $this->createNamespaceGeneratorPlugin(),
            ClassNameGeneratorPlugin::PATTERN => $this->createClassNameGeneratorPlugin(),
            ExtendGeneratorPlugin::PATTERN => $this->createExtendsGeneratorPlugin(),
            AbstractClassGeneratorPlugin::PATTERN => $this->createAbstractClassGeneratorPlugin(),
            SchemaDataGeneratorPlugin::PATTERN => $this->createSchemaDataGeneratorPlugin(),
            PropertyMappingGeneratorPlugin::PATTERN => $this->createPropertyMappingGeneratorPlugin(),
            CollectionItemsGeneratorPlugin::PATTERN => $this->createCollectionItemsGeneratorPlugin(),
        ];
    }

    protected function createClassNameGeneratorPlugin(): SchemaGeneratorPluginInterface
    {
        return new ClassNameGeneratorPlugin(
            $this->getPropertyExplorer()
        );
    }

    protected function createAbstractClassGeneratorPlugin(): SchemaGeneratorPluginInterface
    {
        return new AbstractClassGeneratorPlugin(
            $this->getPropertyExplorer()
        );
    }

    protected function createNamespaceGeneratorPlugin(): SchemaGeneratorPluginInterface
    {
        return new NamespaceGeneratorPlugin(
            $this->getPropertyExplorer()
        );
    }

    protected function createSchemaDataGeneratorPlugin(): SchemaGeneratorPluginInterface
    {
        return new SchemaDataGeneratorPlugin(
            $this->getPropertyExplorer()
        );
    }

    protected function createPropertyMappingGeneratorPlugin(): SchemaGeneratorPluginInterface
    {
        return new PropertyMappingGeneratorPlugin(
            $this->getPropertyExplorer()
        );
    }

    protected function createCollectionItemsGeneratorPlugin(): SchemaGeneratorPluginInterface
    {
        return new CollectionItemsGeneratorPlugin(
            $this->getPropertyExplorer()
        );
    }

    protected function createExtendsGeneratorPlugin(): SchemaGeneratorPluginInterface
    {
        return new ExtendGeneratorPlugin(
            $this->getPropertyExplorer()
        );
    }

    protected function getPropertyExplorer(): PropertyExplorerInterface
    {
        return $this->propertyExplorer;
    }
}
