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

    public function __construct(
        SchemaInspectorInterface $schemaInspector,
        SchemaGeneratorInterface $schemaGenerator,
        PluginContainerInterface $pluginContainer
    ) {
        $this->schemaInspector = $schemaInspector;
        $this->schemaGenerator = $schemaGenerator;
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
        foreach ($this->pluginContainer->createPhpFilePlugin() as $plugin) {
            $this->file = $plugin->run($this->file, $this->schema);
        }

        return $this;
    }

    protected function runNamespacePlugins(): self
    {
        foreach ($this->pluginContainer->createNamespacePlugin() as $plugin) {
            $this->namespace = $plugin->run($this->namespace);
        }

        return $this;
    }

    protected function runClassPlugins(): self
    {
        foreach ($this->pluginContainer->createClassPlugins() as $plugin) {
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
}
