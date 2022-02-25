<?php

declare(strict_types = 1);

namespace Popo\Plugin\PropertyPlugin;

use Nette\PhpGenerator\Method;
use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\PropertyPluginInterface;
use Popo\Schema\Property\Property;

class GetPropertyMethodPlugin implements PropertyPluginInterface
{
    //@return array<array{filename: string, schemaName: string, popoName: string, namespace: string}>
    public function run(BuilderPluginInterface $builder, Property $property): void
    {
        $name = $property->getName();

        $body = <<<EOF
return \$this->${name};
EOF;

        $name = $builder->getSchemaInspector()->isBool($property->getType()) ?
            '' . $property->getName() : 'get' . ucfirst($property->getName());

        $method = $builder->getClass()
            ->addMethod($name)
            ->setComment($property->getComment())
            ->setPublic()
            ->setReturnType($builder->getSchemaGenerator()->generatePopoType($builder->getSchema(), $property))
            ->setBody(
                sprintf(
                    $body,
                    $property->getName()
                )
            );

        if ($builder->getSchemaInspector()->isArrayOrMixed($property->getType()) === false) {
            $method->setReturnNullable();
        }

        $this->processItemType($builder, $method, $property);
    }

    protected function processItemType(BuilderPluginInterface $builder, Method $method, Property $property): Property
    {
        if ($property->getItemType()) {
            $returnType = $property->getItemType();
            if ($builder->getSchemaInspector()->isLiteral($property->getItemType())) {
                $returnType = $builder->getSchemaGenerator()->generatePopoItemType(
                    $builder->getSchema(),
                    $property
                );
            }
            $method->setComment(sprintf('@return %s[]', $returnType));
        }

        return $property;
    }
}
