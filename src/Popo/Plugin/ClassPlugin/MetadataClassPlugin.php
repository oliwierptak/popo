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

    /**
     * @param \Popo\Plugin\BuilderPluginInterface $builder
     *
     * @return array<string, array<string, mixed>>
     */
    protected function generateMetadataProperties(BuilderPluginInterface $builder): array
    {
        $metadata = [];

        foreach ($builder->getSchema()->getPropertyCollection() as $property) {
            $propertyName = $property->getName();

            $metadata[$propertyName] = [
                PopoDefinesInterface::SCHEMA_PROPERTY_TYPE => $property->getType(),
                PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT => $property->getDefault(),
                PopoDefinesInterface::SCHEMA_PROPERTY_MAPPING_POLICY => $property->getMappingPolicy(),
                PopoDefinesInterface::SCHEMA_PROPERTY_MAPPING_POLICY_VALUE =>
                    $property->getMappingPolicyValue() ?? $builder->getSchemaMapper()
                        ->mapKeyName($property->getMappingPolicy(), $propertyName),
            ];

            if ($builder->getSchemaInspector()->hasExtra($property)) {
                if ($builder->getSchemaInspector()->isDateTimeProperty($property->getType())) {
                    $extra = new PropertyExtraTimezone((array)$property->getExtra());
                    $metadata[$propertyName][PopoDefinesInterface::PROPERTY_TYPE_EXTRA_FORMAT] = $extra->getFormat();

                    if ($extra->hasTimezone()) {
                        $metadata[$propertyName][PopoDefinesInterface::PROPERTY_TYPE_EXTRA_TIMEZONE] = $extra->getTimezone();
                    }
                }
                else {
                    $metadata[$propertyName][PopoDefinesInterface::SCHEMA_PROPERTY_EXTRA] = $property->getExtra();
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

                $metadata[$propertyName][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = $literalValue;
                continue;
            }

            if ($builder->getSchemaInspector()->isArray($property->getType())) {
                $values = [];

                foreach ($property->getDefault() ?? [] as $key => $defaultValue) {
                    if ($builder->getSchemaInspector()->isLiteral($defaultValue)) {
                        $defaultValue = new Literal($defaultValue);
                    }

                    $values[$key] = $defaultValue;
                }

                $metadata[$propertyName][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = $values;
                continue;
            }

            if ($builder->getSchemaInspector()->isDateTimeProperty($property->getType())) {
                $metadata[$propertyName][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = $property->getDefault();
                continue;
            }

            if ($builder->getSchemaInspector()->isBool($property->getType())) {
                $metadata[$propertyName][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = (bool) $property->getDefault();
                continue;
            }

            if ($builder->getSchemaInspector()->isLiteral($property->getDefault())) {
                $literalValue = new Literal($property->getDefault());

                $metadata[$propertyName][PopoDefinesInterface::SCHEMA_PROPERTY_DEFAULT] = $literalValue;
            }
        }

        return $metadata;
    }
}
