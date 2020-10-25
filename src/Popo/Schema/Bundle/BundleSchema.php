<?php declare(strict_types = 1);

namespace Popo\Schema\Bundle;

use Popo\Schema\Reader\Schema;
use Symfony\Component\Finder\SplFileInfo;

class BundleSchema
{
    protected Schema $schema;

    protected SplFileInfo $filename;

    protected bool $isBundleSchema = false;

    public function __construct(Schema $schema, SplFileInfo $filename)
    {
        $this->schema = $schema;
        $this->filename = $filename;
    }

    public function getSchema(): Schema
    {
        return $this->schema;
    }

    public function getSchemaFilename(): SplFileInfo
    {
        return $this->filename;
    }

    public function isBundleSchema(): bool
    {
        return $this->isBundleSchema;
    }

    public function setIsBundleSchema(bool $isBundleSchema): void
    {
        $this->isBundleSchema = $isBundleSchema;
    }
}
