<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Popo\Schema\Property;
use Popo\Schema\Schema;
use function ucfirst;

class PopoBuilder extends AbstractBuilder
{
    public function build(Schema $schema): void
    {
        $this->buildSchema($schema);

        foreach ($schema->getPropertyCollection() as $property) {
            $this
                ->addProperty($property)
                ->addSetMethod($property)
                ->addParameter($property)
                ->addGetMethod($property)
                ->addRequireByMethod($property)
                ->addHasPropertyValueMethod($property);
        }

        $this
            ->addMetadataShapeConstant()
            ->addToArrayMethod()
            ->addFromArrayMethod()
            ->addUpdateMap()
            ->addIsNewMethod()
            ->addRequireAllMethod();

        $this->save();
    }

    protected function buildSchema(Schema $schema): self
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
        $this->namespace->addUse('UnexpectedValueException');

        $this->class = $this->namespace->addClass($this->schema->getName());

        return $this;
    }

    protected function addMetadataShapeConstant(): self
    {
        $shapeProperties = [];
        $metadata = [];

        foreach ($this->schema->getPropertyCollection() as $property) {
            $metadata[$property->getName()] = [
                'type' => $property->getType(),
                'default' => $property->getDefault(),
            ];
            $shapeProperties[$property->getName()] = $property->getType();

            if ($this->propertyInspector->isPopoProperty($property->getType()) ||
                $this->valueInspector->isConstValue($property->getDefault())) {
                $literalValue = new Literal($property->getDefault());

                $metadata[$property->getName()]['default'] = $literalValue;

                if ($this->valueInspector->isConstValue($property->getDefault()) === false) {
                    $shapeProperties[$property->getName()] = $literalValue;
                }
            }
        }

        $this->class
            ->addConstant(
                'SHAPE_PROPERTIES',
                $shapeProperties
            )
            ->setProtected();

        $this->class
            ->addConstant(
                'METADATA',
                $metadata
            )
            ->setProtected();

        return $this;
    }

    protected function addGetMethod(Property $property): self
    {
        $name = $property->getName();

        $body = <<<EOF
if (static::METADATA['${name}']['type'] === 'popo' && \$this->${name} === null) {
    \$popo = static::METADATA['${name}']['default'];
    \$this->${name} = new \$popo;
}

return \$this->${name};
EOF;

        $this->method = $this->class
            ->addMethod('get' . ucfirst($property->getName()))
            ->setPublic()
            ->setReturnType($this->propertyInspector->generatePopoType($this->schema, $property))
            ->setReturnNullable()
            ->setBody(
                sprintf(
                    $body,
                    $property->getName()
                )
            );

        return $this;
    }

    protected function addSetMethod(Property $property): self
    {
        $this->method = $this->class
            ->addMethod('set' . ucfirst($property->getName()))
            ->setPublic()
            ->setReturnType('self')
            ->setBody(
                sprintf(
                    '$this->%s = $%s; $this->updateMap[\'%s\'] = true; return $this;',
                    $property->getName(),
                    $property->getName(),
                    $property->getName(),
                )

            );

        return $this;
    }

    protected function addRequireByMethod(Property $property): self
    {
        $name = $property->getName();

        $body = <<<EOF
if (\$this->${name} === null) {
    throw new UnexpectedValueException('Required property "${name}" is not set');
}
return \$this->${name};
EOF;

        $this->class
            ->addMethod('require' . ucfirst($property->getName()))
            ->setPublic()
            ->setReturnType($this->propertyInspector->generatePopoType($this->schema, $property))
            ->setBody($body);

        return $this;
    }

    protected function addIsNewMethod(): self
    {
        $body = <<<EOF
return empty(\$this->updateMap) === true;
EOF;

        $this->class
            ->addMethod('isNew')
            ->setPublic()
            ->setReturnType('bool')
            ->setBody($body);

        return $this;
    }

    protected function addRequireAllMethod(): self
    {
        $body = <<<EOF
\$errors = [];

%s

if (empty(\$errors) === false) {
    throw new UnexpectedValueException(
        \implode("\\n", \$errors)
    );
}

return \$this;
EOF;

        $validationBody = <<<EOF
try {
    \$this->require%s();
}
catch (\Throwable \$throwable) {
    \$errors['%s'] = \$throwable->getMessage();
}

EOF;

        $require = '';
        foreach ($this->schema->getPropertyCollection() as $index => $property) {
            $require .= sprintf(
                $validationBody,
                ucfirst($property->getName()),
                ucfirst($property->getName())
            );
        }

        $body = sprintf(
            $body,
            rtrim($require, "\n")
        );

        $this->class
            ->addMethod('requireAll')
            ->setPublic()
            ->setReturnType('self')
            ->setBody($body);

        return $this;
    }

    protected function addHasPropertyValueMethod(Property $property): self
    {
        $name = $property->getName();

        $body = <<<EOF
return \$this->${name} !== null;
EOF;

        $this->class
            ->addMethod('has' . ucfirst($property->getName()))
            ->setPublic()
            ->setReturnType('bool')
            ->setBody($body);

        return $this;
    }

    protected function addToArrayMethod(): self
    {
        $body = "\$data = [\n";
        foreach ($this->schema->getPropertyCollection() as $index => $property) {
            $body .= sprintf(
                "\t'%s' => \$this->%s,\n",
                $property->getName(),
                $property->getName()
            );
        }

        $body .= <<<EOF
];

array_walk(
    \$data,
    function (&\$value, \$name) use (\$data) {
        \$popo = static::METADATA[\$name]['default'];
        if (static::METADATA[\$name]['type'] === 'popo') {
            \$value = \$this->\$name !== null ? \$this->\$name->toArray() : (new \$popo)->toArray();
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

    protected function addFromArrayMethod(): self
    {
        $body = <<<EOF
foreach (static::METADATA as \$name => \$meta) {
    \$value = \$data[\$name] ?? \$this->\$name ?? null;
    \$popoValue = \$meta['default'];

    if (\$popoValue !== null && \$meta['type'] === 'popo') {
        \$popo = new \$popoValue;

        if (is_array(\$value)) {
            \$popo->fromArray(\$value);
        }

        \$value = \$popo;
    }

    \$this->\$name = \$value;
    \$this->updateMap[\$name] = true;
}

return \$this;
EOF;

        $this->class
            ->addMethod('fromArray')
            ->setPublic()
            ->setReturnType('self')
            ->setBody($body)
            ->addParameter('data')
            ->setType('array')
            ->addAttribute(
                'JetBrains\PhpStorm\ArrayShape',
                [new Literal('self::SHAPE_PROPERTIES')]
            );

        return $this;
    }

    protected function addUpdateMap(): self
    {
        $this->class
            ->addProperty('updateMap', [])
            ->setType('array')
            ->setProtected();

        return $this;
    }
}
