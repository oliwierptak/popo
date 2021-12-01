<?php

declare(strict_types = 1);

namespace Popo\Builder;

use Nette\PhpGenerator\Literal;
use Popo\PopoDefinesInterface;
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
            ->addMetadata()
            ->addUpdateMap()
            ->addSetupPopoMethod();

        $this->runPlugins();

        $this->save();

        return $this->generateFilename();
    }

    protected function addMetadata(): self
    {
        $this->class
            ->addConstant(
                'METADATA',
                $this->generateMetadataProperties()
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

        $name = $this->schemaInspector->isBool($property->getType()) ?
            '' . $property->getName() : 'get' . ucfirst($property->getName());

        $this->method = $this->class
            ->addMethod($name)
            ->setComment($property->getComment())
            ->setPublic()
            ->setReturnType($this->schemaInspector->generatePopoType($this->schema, $property))
            ->setBody(
                sprintf(
                    $body,
                    $property->getName()
                )
            );

        if ($this->schemaInspector->isArrayOrMixed($property->getType()) === false) {
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
\$this->setupPopoProperty('${name}');

if (%s) {
    throw new UnexpectedValueException('Required value of "${name}" has not been set');
}
return \$this->${name};
EOF;

        $condition = $this->schemaInspector->isArray($property->getType())
            ? "empty(\$this->${name})"
            : "\$this->${name} === null";

        $body = sprintf($body, $condition);

        $this->class
            ->addMethod('require' . ucfirst($property->getName()))
            ->setComment($property->getComment())
            ->setPublic()
            ->setReturnType($this->schemaInspector->generatePopoType($this->schema, $property))
            ->setBody($body);

        return $this;
    }

    protected function addHasPropertyValueMethod(Property $property): self
    {
        $name = $property->getName();

        $body = <<<EOF
return \$this->${name} !== null;
EOF;

        if ($this->schemaInspector->isArray($property->getType())) {
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

    protected function addSetupPopoMethod(): self
    {
        $body = <<<EOF
if (static::METADATA[\$propertyName]['type'] === 'popo' && \$this->\$propertyName === null) {
    \$popo = static::METADATA[\$propertyName]['default'];
    \$this->\$propertyName = new \$popo;
}
EOF;

        $this->class
            ->addMethod('setupPopoProperty')
            ->setProtected()
            ->setReturnType('void')
            ->setBody($body)
            ->addParameter('propertyName');

        return $this;
    }

    protected function addAddItemMethod(Property $property): self
    {
        if ($property->getItemType() === null || $this->schemaInspector->isArray($property->getType()) === false) {
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
                $this->schemaInspector->generatePopoItemType(
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
            if ($this->schemaInspector->isLiteral($property->getItemType())) {
                $returnType = $this->schemaInspector->generatePopoItemType(
                    $this->schema,
                    $property
                );
            }
            $this->method->setComment(sprintf('@return %s[]', $returnType));
        }

        return $property;
    }

    protected function generateMetadataProperties(): array
    {
        $metadata = [];

        foreach ($this->schema->getPropertyCollection() as $property) {
            $metadata[$property->getName()] = [
                PopoDefinesInterface::SCHEMA_PROPERTY_TYPE => $property->getType(),
                PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT => $property->getDefault(),
            ];

            if ($this->schemaInspector->isPopoProperty($property->getType())) {
                $literalValue = new Literal(
                    $this->schemaInspector->generatePopoType(
                        $this->schema,
                        $property,
                        false
                    )
                );

                $metadata[$property->getName()][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = $literalValue;
            }
            else {
                if ($this->schemaInspector->isLiteral($property->getDefault())) {
                    $literalValue = new Literal($property->getDefault());

                    $metadata[$property->getName()][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = $literalValue;
                }
            }
        }

        return $metadata;
    }
}
