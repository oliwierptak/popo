<?php

declare(strict_types = 1);

namespace Popo\Schema\Bundle;

use Popo\Schema\Reader\SchemaInterface;
use Symfony\Component\Finder\SplFileInfo;

class BundleSchemaFactory implements BundleSchemaFactoryInterface
{
    public function createBundleSchema(SchemaInterface $schema, SplFileInfo $filename): BundleSchemaInterface
    {
        return new BundleSchema($schema, $filename);
    }
}
