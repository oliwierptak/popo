<?php

declare(strict_types = 1);

namespace Popo\Schema\Bundle;

use Popo\Schema\Reader\SchemaInterface;
use Symfony\Component\Finder\SplFileInfo;

interface BundleSchemaFactoryInterface
{
    public function createBundleSchema(SchemaInterface $schema, SplFileInfo $filename): BundleSchemaInterface;
}
