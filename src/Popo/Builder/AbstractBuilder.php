<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Popo\Plugin\BuilderPluginInterface;
use Popo\Schema\Property;
use Popo\Schema\Schema;
use Popo\Schema\SchemaInspector;

abstract class AbstractBuilder implements BuilderPluginInterface
{
    protected Schema $schema;
    protected PhpFile $file;
    protected PhpNamespace $namespace;
    protected ClassType $class;
    protected Method $method;
    protected SchemaInspector $schemaInspector;
    protected FileWriter $fileWriter;
    /**
     * @var \Popo\Plugin\ClassPluginInterface[]
     */
    protected array $classPluginCollection = [];
    /**
     * @var \Popo\Plugin\PropertyPluginInterface[]
     */
    protected array $propertyMethodPluginCollection = [];

    /**
     * @throws \Throwable
     */
    abstract public function build(Schema $schema): string;

    public function __construct(
        SchemaInspector $schemaInspector,
        FileWriter $fileWriter,
        array $classPluginCollection,
        array $propertyMethodPluginCollection
    ) {
        $this->schemaInspector = $schemaInspector;
        $this->fileWriter = $fileWriter;
        $this->classPluginCollection = $classPluginCollection;
        $this->propertyMethodPluginCollection = $propertyMethodPluginCollection;
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

    public function getSchemaInspector(): SchemaInspector
    {
        return $this->schemaInspector;
    }

    protected function buildSchema(Schema $schema): self
    {
        $this->schema = $schema;

        $this->file = new PhpFile();
        $this->file->setStrictTypes();

        if ($schema->getConfig()->getComment() !== null) {
            $this->file->addComment($schema->getConfig()->getComment());
        }

        $this->namespace = $this->file->addNamespace(
            new PhpNamespace(
                $schema->getConfig()->getNamespace()
            )
        );

        $this->buildClass();

        return $this;
    }

    protected function buildClass(): self
    {
        $this->namespace->addUse('UnexpectedValueException');

        $this->class = $this->namespace->addClass($this->schema->getName());

        return $this;
    }

    protected function runClassPlugins(): void
    {
        foreach ($this->classPluginCollection as $plugin) {
            $plugin->run($this);
        }
    }

    protected function runPropertyMethodPlugins(Property $property): void
    {
        foreach ($this->propertyMethodPluginCollection as $plugin) {
            $plugin->run($this, $property);
        }
    }
}
