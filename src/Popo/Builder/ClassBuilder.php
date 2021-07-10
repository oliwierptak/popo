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
use function ucfirst;

class ClassBuilder
{
    protected ClassType $class;
    protected Method $method;
    protected Schema $schema;
    protected PhpFile $file;
    protected PhpNamespace $namespace;

    public function __construct(
        protected SchemaValueInspector $valueInspector,
        protected SchemaPropertyInspector $propertyInspector
    ) {
    }

    public function build(Schema $schema): self
    {
        $this->schema = $schema;

        $this->file = new PhpFile();
        $this->file->addComment('This file is auto-generated.'); //TODO add file section to config
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

    public function addSchemaShapeConstant(): self
    {
        $properties = [];
        $metadata = [];

        foreach ($this->schema->getPropertyCollection() as $property) {
            $type = $property->getSchema()->getType();
            $metadata[$property->getSchema()->getName()] = $type;

            if ($this->propertyInspector->isPopoProperty($property->getSchema()->getType())) {
                $type = new Literal($property->getSchema()->getDefault());
            }

            $properties[$property->getSchema()->getName()] = $type;
        }

        $this->class
            ->addConstant(
                'SHAPE_PROPERTIES',
                $properties
            )
            ->setProtected();

        $this->class
            ->addConstant(
                'SHAPE_METADATA',
                $metadata
            )
            ->setProtected();

        return $this;
    }

    public function addProperty(Property $property): self
    {
        $value = $property->getValue() ?? $property->getSchema()->getDefault();
        if ($this->propertyInspector->isPopoProperty($property->getSchema()->getType())) {
            $value = null;
        }

        if ($this->valueInspector->isConstValue($property->getSchema()->getDefault())) {
            $value = new Literal($property->getSchema()->getDefault());
        }

        $this->class
            ->addProperty($property->getSchema()->getName(), $value)
            ->setProtected()
            ->setNullable(true)
            ->setType($this->generatePropertyType($property))
            ->setComment($property->getSchema()->getDocblock());

        return $this;
    }

    public function addGetMethod(Property $property): self
    {
        $this->method = $this->class
            ->addMethod('get' . ucfirst($property->getSchema()->getName()))
            ->setPublic()
            ->setReturnType($this->generateMethodReturnType($property))
            ->setReturnNullable()
            ->setBody('return $this->' . $property->getSchema()->getName() . ';');

        return $this;
    }

    public function addSetMethod(Property $property): self
    {
        $this->method = $this->class
            ->addMethod('set' . ucfirst($property->getSchema()->getName()))
            ->setPublic()
            ->setReturnType('self')
            ->setBody(
                '$this->' . $property->getSchema()->getName() . ' = $' . $property->getSchema()->getName(
                ) . '; return $this;'
            );

        return $this;
    }

    public function addParameter(Property $property): self
    {
        $this->method
            ->addParameter($property->getSchema()->getName())
            ->setType($this->generateMethodParameterType($property))
            ->setNullable();

        return $this;
    }

    public function addToArrayMethod(): self
    {
        $body = "\$data = [\n";
        foreach ($this->schema->getPropertyCollection() as $index => $property) {
            $body .= sprintf(
                "\t'%s' => \$this->%s,\n",
                $property->getSchema()->getName(),
                $property->getSchema()->getName()
            );
        }

        $body .= <<<EOF
];

\array_walk(
    \$data,
    function (&\$value, \$name) use (\$data) {
        \$type = self::SHAPE_PROPERTIES[\$name];
        if (self::SHAPE_METADATA[\$name] === 'popo') {
            \$value = \$this->\$name !== null ? \$this->\$name->toArray() : (new \$type)->toArray();
        }
    }
);

return \$data;
EOF;

        $this->class
            ->addMethod('toArray')
            ->addAttribute(
                'JetBrains\PhpStorm\ArrayShape',
                [new Literal('self::SHAPE_PROPERTIES')]
            )
            ->setPublic()
            ->setReturnType('array')
            ->setBody($body);

        return $this;
    }

    public function addFromArrayMethod(): self
    {
        $body = "
foreach (self::SHAPE_METADATA as \$name => \$type) {
    \$value = \$data[\$name] ?? \$this->\$name ?? null;
    \$popoValue = self::SHAPE_PROPERTIES[\$name];

    if (\$popoValue !== null && \$type === 'popo') {
        \$popo = new \$popoValue;

        if (is_array(\$value)) {
            \$popo->fromArray(\$value);
        }
        
        \$value = \$popo;
    }

    \$this->\$name = \$value;
}

return \$this;
        ";

        $this->class
            ->addMethod('fromArray')
            ->addAttribute(
                'JetBrains\PhpStorm\ArrayShape',
                [new Literal('self::SHAPE_PROPERTIES')]
            )
            ->setPublic()
            ->setReturnType('self')
            ->setBody($body)
            ->addParameter('data')
            ->setType('array');

        return $this;
    }

    public function print(): string
    {
        return (new PsrPrinter)->printFile($this->file);
    }

    protected function generateMethodParameterType(Property $property): string
    {
        if ($this->propertyInspector->isPopoProperty($property->getSchema()->getType()) === false) {
            return $property->getSchema()->getType();
        }

        $namespace = $this->schema->getNamespace();
        $name = str_replace('::class', '', $property->getSchema()->getDefault());
        if ($this->valueInspector->isFqcn($property->getSchema()->getDefault())) {
            return $property->getSchema()->getDefault();
        }

        $sep = $namespace !== '' ? '\\' : '';

        return sprintf(
            '%s%s%s',
            $namespace,
            $sep,
            $name
        );
    }

    protected function generateMethodReturnType(Property $property): string
    {
        if ($this->propertyInspector->isPopoProperty($property->getSchema()->getType())) {
            $namespace = $this->propertyInspector->expandNamespaceForParameter(
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
        if ($this->propertyInspector->isPopoProperty($property->getSchema()->getType()) === false) {
            return $property->getSchema()->getType();
        }

        $namespace = $this->propertyInspector->expandNamespaceForParameter(
            $this->schema,
            $property->getSchema()
        );

        return sprintf(
            '%s\\%s',
            $namespace,
            str_replace('::class', '', $property->getSchema()->getDefault())
        );
    }
}
