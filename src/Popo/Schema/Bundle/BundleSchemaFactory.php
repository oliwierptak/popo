<?php

declare(strict_types = 1);

namespace Popo\Schema\Bundle;

use Popo\Schema\Reader\Schema;
use Symfony\Component\Finder\SplFileInfo;

class BundleSchemaFactory
{
    public function createBundleSchema(Schema $schema, SplFileInfo $filename): BundleSchema
    {
        return new BundleSchema($schema, $filename);
    }
}
