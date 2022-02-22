<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Nette\PhpGenerator\Literal;
use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;
use Popo\PopoDefinesInterface;
use Popo\Schema\Property\PropertyExtraTimezone;

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

            if ($builder->getSchemaInspector()->hasExtra($property)) {
                if ($builder->getSchemaInspector()->isDateTimeProperty($property->getType())) {
                    $extra = new PropertyExtraTimezone($property->getExtra());
                    $metadata[$property->getName(
                    )][PopoDefinesInterface::PROPERTY_TYPE_EXTRA_FORMAT] = $extra->getFormat();

                    if ($extra->hasTimezone()) {
                        $metadata[$property->getName(
                        )][PopoDefinesInterface::PROPERTY_TYPE_EXTRA_TIMEZONE] = $extra->getTimezone();
                    }
                }
                else {
                    $metadata[$property->getName()][PopoDefinesInterface::SCHEMA_PROPERTY_EXTRA] = $property->getExtra();
                }
            }

            if ($builder->getSchemaInspector()->isPopoProperty($property->getType())) {
                $literalValue = new Literal(
                    $builder->getSchemaGenerator()->generatePopoType(
                        $builder->getSchema(),
                        $property,
                        false
                    )
                );

                $metadata[$property->getName()][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = $literalValue;
                continue;
            }

            if ($builder->getSchemaInspector()->isDateTimeProperty($property->getType())) {
                $metadata[$property->getName()][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = $property->getDefault();
                continue;
            }

            if ($builder->getSchemaInspector()->isBool($property->getType())) {
                $metadata[$property->getName()][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = (bool) $property->getDefault();
                continue;
            }

            if ($builder->getSchemaInspector()->isLiteral($property->getDefault())) {
                $literalValue = new Literal($property->getDefault());

                $metadata[$property->getName()][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = $literalValue;
            }
        }

        return $metadata;
    }
}
