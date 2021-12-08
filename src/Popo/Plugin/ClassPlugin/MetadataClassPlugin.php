<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Nette\PhpGenerator\Literal;
use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;
use Popo\PopoDefinesInterface;

class MetadataClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $builder->getClass()
            ->addConstant(
                'METADATA',
                $this->generateMetadataProperties($builder)
            )
            ->setProtected();
    }

    protected function generateMetadataProperties(BuilderPluginInterface $builder): array
    {
        $metadata = [];

        foreach ($builder->getSchema()->getPropertyCollection() as $property) {
            $metadata[$property->getName()] = [
                PopoDefinesInterface::SCHEMA_PROPERTY_TYPE => $property->getType(),
                PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT => $property->getDefault(),
            ];

            if ($builder->getSchemaInspector()->isPopoProperty($property->getType())) {
                $literalValue = new Literal(
                    $builder->getSchemaInspector()->generatePopoType(
                        $builder->getSchema(),
                        $property,
                        false
                    )
                );

                $metadata[$property->getName()][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = $literalValue;
            }
            else {
                if ($builder->getSchemaInspector()->isLiteral($property->getDefault())) {
                    $literalValue = new Literal($property->getDefault());

                    $metadata[$property->getName()][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = $literalValue;
                }
            }
        }

        return $metadata;
    }
}
