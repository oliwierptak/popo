<?php

declare(strict_types = 1);

namespace Popo\Schema\Validator;

use Popo\Schema\Bundle\BundleSchemaInterface;
use Popo\Schema\Reader\PropertyInterface;
use Popo\Schema\Validator\Exception\NotBundleSchemaException;
use Popo\Schema\Validator\Exception\NotUniquePropertyException;

class SchemaValidator implements SchemaValidatorInterface
{
    public function assertIsBundleSchema(BundleSchemaInterface $bundleSchema): void
    {
        if (!$bundleSchema->isBundleSchema()) {
            throw new NotBundleSchemaException(\sprintf(
                'Schema: "%s" is not bundle schema',
                $bundleSchema->getSchemaFilename()
            ));
        }
    }

    public function assertProperties(
        BundleSchemaInterface $bundleSchema,
        array $bundleSchemaProperties,
        BundleSchemaInterface $additionalBundleSchema,
        array $additionalProperties
    ): void {
        foreach ($bundleSchemaProperties as $bundleSchemaProperty) {
            $this->assertUniqueProperties(
                $bundleSchemaProperty,
                $bundleSchema,
                $additionalBundleSchema,
                $additionalProperties
            );
        }
    }

    /**
     * @param \Popo\Schema\Reader\PropertyInterface $bundleSchemaProperty
     * @param \Popo\Schema\Bundle\BundleSchemaInterface $bundleSchema
     * @param \Popo\Schema\Bundle\BundleSchemaInterface $additionalBundleSchema
     * @param array $additionalProperties
     *
     * @throws \Popo\Schema\Validator\Exception\NotUniquePropertyException
     *
     * @return void
     */
    protected function assertUniqueProperties(
        PropertyInterface $bundleSchemaProperty,
        BundleSchemaInterface $bundleSchema,
        BundleSchemaInterface $additionalBundleSchema,
        array $additionalProperties
    ): void {
        foreach ($additionalProperties as $additionalSchemaProperty) {
            if (\strcasecmp($bundleSchemaProperty->getName(), $additionalSchemaProperty->getName()) === 0) {
                throw new NotUniquePropertyException(\sprintf(
                    'The property: "%s" is already defined in "%s" and cannot be redefined in "%s"',
                    $bundleSchemaProperty->getName(),
                    $bundleSchema->getSchemaFilename(),
                    $additionalBundleSchema->getSchemaFilename()
                ));
            }
        }
    }
}
