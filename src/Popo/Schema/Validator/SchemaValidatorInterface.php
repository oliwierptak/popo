<?php

declare(strict_types = 1);

namespace Popo\Schema\Validator;

use Popo\Schema\Bundle\BundleSchemaInterface;

interface SchemaValidatorInterface
{
    /**
     * @param \Popo\Schema\Bundle\BundleSchemaInterface $bundleSchema
     *
     * @throws \Popo\Schema\Validator\Exception\NotBundleSchemaException
     *
     * @return void
     */
    public function assertIsBundleSchema(BundleSchemaInterface $bundleSchema): void;

    /**
     * @param \Popo\Schema\Bundle\BundleSchemaInterface $bundleSchema
     * @param array $bundleSchemaProperties
     * @param \Popo\Schema\Bundle\BundleSchemaInterface $additionalBundleSchema
     * @param array $additionalProperties
     *
     * @throws \Popo\Schema\Validator\Exception\NotUniquePropertyException
     *
     * @return void
     */
    public function assertProperties(
        BundleSchemaInterface $bundleSchema,
        array $bundleSchemaProperties,
        BundleSchemaInterface $additionalBundleSchema,
        array $additionalProperties
    ): void;
}
