<?php

declare(strict_types = 1);

namespace Popo\Schema\Bundle;

use Popo\Schema\Reader\SchemaInterface;
use Symfony\Component\Finder\SplFileInfo;

interface BundleSchemaInterface
{
    public function getSchema(): SchemaInterface;

    public function getSchemaFilename(): SplFileInfo;

    public function isBundleSchema(): bool;

    public function setIsBundleSchema(bool $isBundleSchema): void;
}
