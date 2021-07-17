<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Nette\PhpGenerator\Literal;
use Popo\Schema\Property;
use Popo\Schema\Schema;
use function ucfirst;

class PopoBuilder extends AbstractBuilder
{
    public function build(Schema $schema): string
    {
        $this->buildSchema($schema);

        foreach ($schema->getPropertyCollection() as $property) {
            $this
                ->addProperty($property)
                ->addSetMethod($property)
                ->addParameter($property)
                ->addGetMethod($property)
                ->addRequireByMethod($property)
                ->addHasPropertyValueMethod($property)
                ->addAddItemMethod($property);
        }

        $this
            ->addImplement()
            ->addExtend()
            ->addMetadataShapeConstant()
            ->addToArrayMethod()
            ->addFromArrayMethod()
            ->addUpdateMap()
            ->addIsNewMethod()
            ->addRequireAllMethod();

        $this->save();

        return $this->generateFilename();
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
            if ($this->propertyInspector->isPropertyNullable($property)) {
                $shapeProperties[$property->getName()] = 'null|' . $property->getType();
            }

            if ($this->propertyInspector->isPopoProperty($property->getType())) {
                $literalValue = new Literal(
                    $this->propertyInspector->generatePopoType(
                        $this->schema,
                        $property,
                        false
                    )
                );

                $shapeProperties[$property->getName()] = $literalValue;
                $metadata[$property->getName()]['default'] = $literalValue;
            }
            else {
                if ($this->propertyInspector->isLiteral($property->getDefault())) {
                    $literalValue = new Literal($property->getDefault());

                    $metadata[$property->getName()]['default'] = $literalValue;
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
return \$this->${name};
EOF;

        $prefix = $this->propertyInspector->isBool($property->getType()) ? 'is' : 'get';
        $this->method = $this->class
            ->addMethod($prefix . ucfirst($property->getName()))
            ->setComment($property->getComment())
            ->setPublic()
            ->setReturnType($this->propertyInspector->generatePopoType($this->schema, $property))
            ->setBody(
                sprintf(
                    $body,
                    $property->getName()
                )
            );

        if ($this->propertyInspector->isArrayOrMixed($property->getType()) === false) {
            $this->method->setReturnNullable();
        }

        $this->processItemType($property);

        return $this;
    }

    protected function addSetMethod(Property $property): self
    {
        $this->method = $this->class
            ->addMethod('set' . ucfirst($property->getName()))
            ->setComment($property->getComment())
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
if (static::METADATA['${name}']['type'] === 'popo' && \$this->${name} === null) {
    \$popo = static::METADATA['${name}']['default'];
    \$this->${name} = new \$popo;
}

if (%s) {
    throw new UnexpectedValueException('Required value of "${name}" has not been set');
}
return \$this->${name};
EOF;

        $condition = $this->propertyInspector->isArray($property->getType())
            ? "empty(\$this->${name})"
            : "\$this->${name} === null";

        $body = sprintf($body, $condition);

        $this->class
            ->addMethod('require' . ucfirst($property->getName()))
            ->setComment($property->getComment())
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
        implode("\\n", \$errors)
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
                $property->getName()
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

        if ($this->propertyInspector->isArray($property->getType())) {
            $name = $property->getItemName() ?? $property->getName();
            $name = $name . 'Collection';

            $body = <<<EOF
return !empty(\$this->${name});
EOF;
        }

        $this->class
            ->addMethod('has' . ucfirst($name))
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

    protected function addAddItemMethod(Property $property): self
    {
        if ($property->getItemType() === null || $this->propertyInspector->isArray($property->getType()) === false) {
            return $this;
        }

        $name = $property->getName();

        $body = <<<EOF
\$this->${name}[] = \$item;

\$this->updateMap['${name}'] = true;

return \$this;
EOF;

        $name = $property->getItemName() ?? $property->getName() . 'Item';

        $this->class
            ->addMethod('add' . ucfirst($name))
            ->setPublic()
            ->setReturnType('self')
            ->setBody($body)
            ->addParameter('item')
            ->setType(
                $this->propertyInspector->generatePopoItemType(
                    $this->schema,
                    $property
                )
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

    /**
     * @param \Popo\Schema\Property $property
     *
     * @return Property
     */
    protected function processItemType(Property $property): Property
    {
        if ($property->getItemType()) {
            $returnType = $property->getItemType();
            if ($this->propertyInspector->isLiteral($property->getItemType())) {
                $returnType = $this->propertyInspector->generatePopoItemType(
                    $this->schema,
                    $property,
                );
            }
            $this->method->setComment(sprintf('@return %s[]', $returnType));
        }

        return $property;
    }
}
