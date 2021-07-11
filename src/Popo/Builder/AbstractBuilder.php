<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use Popo\Inspector\SchemaValueInspector;
use Popo\Inspector\SchemaPropertyInspector;
use Popo\Schema\Property;
use Popo\Schema\Schema;
use function fwrite;
use function pathinfo;
use function unlink;
use const PATHINFO_DIRNAME;

abstract class AbstractBuilder
{
    protected Schema $schema;
    protected PhpFile $file;
    protected PhpNamespace $namespace;
    protected ClassType $class;
    protected Method $method;

    abstract public function build(Schema $schema): void;

    public function __construct(
        protected SchemaValueInspector $valueInspector,
        protected SchemaPropertyInspector $propertyInspector
    ) {
    }

    protected function buildSchema(Schema $schema): self
    {
        $this->schema = $schema;

        $this->file = new PhpFile();
        $this->file->addComment('This file is auto-generated.'); //TODO add file section to config
        $this->file->setStrictTypes();

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

    protected function addProperty(Property $property): self
    {
        $value = $property->getDefault();
        if ($this->propertyInspector->isPopoProperty($property->getType())) {
            $value = null;
        }
        else {
            if ($this->valueInspector->isLiteral($property->getDefault())) {
                $value = new Literal($property->getDefault());
            }
        }

        $this->class
            ->addProperty($property->getName(), $value)
            ->setProtected()
            ->setNullable(true)
            ->setType($this->propertyInspector->generatePopoType($this->schema, $property))
            ->setComment($property->getComment());

        return $this;
    }

    protected function addParameter(Property $property): self
    {
        $this->method
            ->addParameter($property->getName())
            ->setType($this->propertyInspector->generatePopoType($this->schema, $property))
            ->setNullable();

        return $this;
    }

    public function print(): string
    {
        return (new PsrPrinter)->printFile($this->file);
    }

    public function save(): void
    {
        try {
            $filename = sprintf(
                '%s/%s/%s.php',
                $this->schema->getConfig()->getOutputPath(),
                str_replace('\\', '/', $this->schema->getConfig()->getNamespace()),
                $this->schema->getName()
            );

            @mkdir(pathinfo($filename, PATHINFO_DIRNAME), 0775, true);
            $handle = fopen($filename, 'w');
            fwrite($handle, $this->print());
        }
        finally {
            fclose($handle);
        }
    }

    public function remove(): void
    {
        $filename = sprintf(
            '%s/%s/%s.php',
            $this->schema->getConfig()->getOutputPath(),
            str_replace('\\', '/', $this->schema->getConfig()->getNamespace()),
            $this->schema->getName()
        );

        @unlink($filename);
    }
}
