<?php

declare(strict_types = 1);

namespace Popo\Builder;

use JetBrains\PhpStorm\Pure;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use Popo\Generator\SchemaReader;
use Popo\Generator\SchemaWriter;
use Popo\PopoDefinesInterface;
use Popo\Schema\Property;
use Popo\Schema\Schema;

class ClassBuilder
{
    protected ClassType $class;
    protected Method $method;
    protected Schema $schema;
    protected PhpFile $file;
    protected PhpNamespace $namespace;

    public function __construct(
        protected SchemaReader $schemaReader,
        protected SchemaWriter $valueTypeWriter
    ) {
    }

    public function build(Schema $schema): self
    {
        $this->schema = $schema;

        $this->file = new PhpFile();
        $this->file->addComment('This file is auto-generated.');
        $this->file->setStrictTypes();

        $this->namespace = $this->file->addNamespace(
            new PhpNamespace(
                $schema->getNamespace()
            )
        );

        $this->buildClass();

        return $this;
    }

    protected function buildClass(): self
    {
        $this->class = $this->namespace->addClass($this->schema->getName());

        return $this;
    }

    public function addProperty(Property $property): self
    {
        $value = $property->getValue() ?? $property->getSchema()->getDefault();
        if ($this->schemaReader->isPopoProperty($property->getSchema()->getType())) {
            $value = null;
        }

        if ($this->schemaReader->isConstValue($property->getSchema()->getDefault())) {
            $value = new Literal($property->getSchema()->getDefault());
        }

        $this->class
            ->addProperty($property->getSchema()->getName(), $value)
            ->setProtected()
            ->setType($this->generatePropertyType($property))
            ->setComment($property->getSchema()->getDocblock());

        return $this;
    }

    public function addGetMethod(Property $property): self
    {
        $this->method = $this->class
            ->addMethod('get' . \ucfirst($property->getSchema()->getName()))
            ->setPublic()
            ->setReturnType($this->generateMethodReturnType($property))
            ->setReturnNullable()
            ->setBody('return $this->' . $property->getSchema()->getName() . ';');

        return $this;
    }

    public function addSetMethod(Property $property): self
    {
        $this->method = $this->class
            ->addMethod('set' . \ucfirst($property->getSchema()->getName()))
            ->setPublic()
            ->setReturnType('self')
            ->setBody('$this->' . $property->getSchema()->getName() . ' = $value; return $this;');

        return $this;
    }

    public function addParameter(Property $property): self
    {
        $this->method
            ->addParameter($property->getSchema()->getName())
            ->setType($this->generateMethodParameter($property))
            ->setNullable();

        return $this;
    }

    public function print(): string
    {
        return (new PsrPrinter)->printFile($this->file);
    }

    protected function generateMethodParameter(Property $property): string
    {
        if ($this->schemaReader->isPopoProperty($property->getSchema()->getType())) {
            $namespace = $this->valueTypeWriter->expandNamespaceForParameter(
                $this->schema,
                $property->getSchema()
            );

            return sprintf(
                '%s\\%s',
                $namespace,
                str_replace('::class', '', $property->getSchema()->getDefault())
            );
        }

        return $property->getSchema()->getType();
    }

    protected function generateMethodReturnType(Property $property): string
    {
        if ($this->schemaReader->isPopoProperty($property->getSchema()->getType())) {
            $namespace = $this->valueTypeWriter->expandNamespaceForParameter(
                $this->schema,
                $property->getSchema()
            );

            return sprintf(
                '%s\\%s',
                $namespace,
                str_replace('::class', '', $property->getSchema()->getDefault())
            );
        }

        return $property->getSchema()->getType();
    }

    protected function generatePropertyType(Property $property): string
    {
        if ($this->schemaReader->isPopoProperty($property->getSchema()->getType())) {
            $namespace = $this->valueTypeWriter->expandNamespaceForParameter(
                $this->schema,
                $property->getSchema()
            );

            return sprintf(
                '%s\\%s',
                $namespace,
                str_replace('::class', '', $property->getSchema()->getDefault())
            );
        }

        return $property->getSchema()->getType();
    }

    #[Pure] protected function generatePropertyValue(Property $property): mixed
    {
        if ($this->schemaReader->isPopoProperty($property->getSchema()->getType())) {
            return null;
        }

        return $property->getSchema()->getDefault();
    }

    #[Pure] protected function isPopoProperty(Property $property): bool
    {
        return $property->getSchema()->getType() === PopoDefinesInterface::PROPERTY_TYPE_POPO;
    }
}
