<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\PluginContainerInterface;
use Popo\Schema\Generator\SchemaGeneratorInterface;
use Popo\Schema\Inspector\SchemaInspectorInterface;
use Popo\Schema\Mapper\SchemaMapperInterface;
use Popo\Schema\Schema;

abstract class AbstractBuilder implements BuilderPluginInterface
{
    protected Schema $schema;
    protected PhpFile $file;
    protected PhpNamespace $namespace;
    protected ClassType $class;
    protected Method $method;
    protected SchemaInspectorInterface $schemaInspector;
    protected SchemaGeneratorInterface $schemaGenerator;
    protected PluginContainerInterface $pluginContainer;
    protected SchemaMapperInterface $schemaMapper;

    public function __construct(
        SchemaInspectorInterface $schemaInspector,
        SchemaGeneratorInterface $schemaGenerator,
        SchemaMapperInterface $schemaMapper,
        PluginContainerInterface $pluginContainer,
    )
    {
        $this->schemaInspector = $schemaInspector;
        $this->schemaGenerator = $schemaGenerator;
        $this->schemaMapper = $schemaMapper;
        $this->pluginContainer = $pluginContainer;
    }

    abstract protected function buildPhpFile(): self;

    abstract protected function buildNamespace(): self;

    abstract protected function buildProperties(): self;

    abstract protected function buildClass(): self;

    public function build(Schema $schema): BuilderPluginInterface
    {
        $this->schema = $schema;

        $this
            ->buildPhpFile()
            ->runPhpFilePlugins()
            ->buildNamespace()
            ->runNamespacePlugins()
            ->buildClass()
            ->runClassPlugins()
            ->buildProperties()
            ->runPropertyPlugins();

        return $this;
    }

    protected function runPhpFilePlugins(): self
    {
        $plugins =  $this->pluginContainer->createPhpFilePlugin(
            $this->schema->getConfig()->getPhpFilePluginCollection()
        );

        foreach ($plugins as $plugin) {
            $this->file = $plugin->run($this->file, $this->schema);
        }

        return $this;
    }

    protected function runNamespacePlugins(): self
    {
        $plugins =  $this->pluginContainer->createNamespacePlugin(
            $this->schema->getConfig()->getNamespacePluginCollection()
        );

        foreach ($plugins as $plugin) {
            $this->namespace = $plugin->run($this, $this->namespace);
        }

        return $this;
    }

    protected function runClassPlugins(): self
    {
        $plugins =  $this->pluginContainer->createClassPlugins(
            $this->schema->getConfig()->getClassPluginCollection()
        );

        foreach ($plugins as $plugin) {
            $plugin->run($this);
        }

        return $this;
    }

    protected function runPropertyPlugins(): self
    {
        foreach ($this->schema->getPropertyCollection() as $property) {
            foreach ($this->pluginContainer->createPropertyPlugins() as $plugin) {
                $plugin->run($this, $property);
            }
        }

        return $this;
    }

    public function getSchema(): Schema
    {
        return $this->schema;
    }

    public function getFile(): PhpFile
    {
        return $this->file;
    }

    public function getNamespace(): PhpNamespace
    {
        return $this->namespace;
    }

    public function getClass(): ClassType
    {
        return $this->class;
    }

    public function getSchemaInspector(): SchemaInspectorInterface
    {
        return $this->schemaInspector;
    }

    public function getSchemaGenerator(): SchemaGeneratorInterface
    {
        return $this->schemaGenerator;
    }

    public function getSchemaMapper(): SchemaMapperInterface
    {
        return $this->schemaMapper;
    }
}
