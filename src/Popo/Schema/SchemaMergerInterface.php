<?php

declare(strict_types = 1);

namespace Popo\Schema;

interface SchemaMergerInterface
{
    /**
     * @param array $bundleSchemaCollection
     *
     * @throws \Popo\Schema\Validator\Exception\NotBundleSchemaException
     * @throws \Popo\Schema\Validator\Exception\NotUniquePropertyException
     *
     * @return \Popo\Schema\Bundle\BundleSchemaInterface[]
     */
    public function merge(array $bundleSchemaCollection): array;
}
