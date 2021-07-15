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
use Popo\Schema\SchemaInspector;
use Popo\Schema\Property;
use Popo\Schema\Schema;
use function fwrite;
use function pathinfo;
use const PATHINFO_DIRNAME;

abstract class AbstractBuilder
{
    protected Schema $schema;
    protected PhpFile $file;
    protected PhpNamespace $namespace;
    protected ClassType $class;
    protected Method $method;

    abstract public function build(Schema $schema): string;

    public function __construct(
        protected SchemaInspector $propertyInspector
    ) {
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

    protected function addProperty(Property $property): self
    {
        $value = $property->getDefault();
        if ($this->propertyInspector->isPopoProperty($property->getType())) {
            $value = null;
        }
        else {
            if ($this->propertyInspector->isLiteral($property->getDefault())) {
                $value = new Literal($property->getDefault());
            }
        }

        if ($value === null && $this->propertyInspector->isArray($property->getType())) {
            $value = [];
        }

        $this->class
            ->addProperty($property->getName(), $value)
            ->setComment($property->getComment())
            ->setProtected()
            ->setNullable($this->isPropertyNullable($property))
            ->setType($this->propertyInspector->generatePopoType($this->schema, $property))
            ->setComment($property->getComment());

        return $this;
    }

    protected function addParameter(Property $property): self
    {
        $nullable = $this->isPropertyNullable($property);

        $this->method
            ->addParameter($property->getName())
            ->setType($this->propertyInspector->generatePopoType($this->schema, $property))
            ->setNullable($nullable);

        return $this;
    }

    protected function addExtend(): self
    {
        if ($this->schema->getConfig()->getExtend() !== null) {
            $extend = str_replace('::class', '', $this->schema->getConfig()->getExtend());
            $this->class->addExtend($extend);
        }

        return $this;
    }

    protected function addImplement(): self
    {
        if ($this->schema->getConfig()->getImplement() !== null) {
            $implement = str_replace('::class', '', $this->schema->getConfig()->getImplement());
            $this->class->addImplement($implement);
        }

        return $this;
    }

    public function print(): string
    {
        return (new PsrPrinter)->printFile($this->file);
    }

    public function save(): void
    {
        try {
            $filename = $this->generateFilename();

            @mkdir(pathinfo($filename, PATHINFO_DIRNAME), 0775, true);

            $handle = fopen($filename, 'w');
            fwrite($handle, $this->print());
        }
        finally {
            fclose($handle);
        }
    }

    public function generateFilename(): string
    {
        return sprintf(
            '%s/%s/%s.php',
            $this->schema->getConfig()->getOutputPath(),
            str_replace('\\', '/', $this->schema->getConfig()->getNamespace()),
            $this->schema->getName()
        );
    }

    #[Pure] protected function isPropertyNullable(Property $property): bool
    {
        return $this->propertyInspector->isArrayOrMixed($property->getType()) === false ||
            $this->propertyInspector->isPopoProperty($property->getType());
    }
}
