<?php

declare(strict_types = 1);

namespace Popo\Schema\Bundle;

use Popo\Schema\Reader\SchemaInterface;
use Symfony\Component\Finder\SplFileInfo;

class BundleSchema implements BundleSchemaInterface
{
    /**
     * @var \Popo\Schema\Reader\SchemaInterface
     */
    protected $schema;

    /**
     * @var \Symfony\Component\Finder\SplFileInfo
     */
    protected $filename;

    /**
     * @var bool
     */
    protected $isBundleSchema = false;

    public function __construct(SchemaInterface $schema, SplFileInfo $filename)
    {
        $this->schema = $schema;
        $this->filename = $filename;
    }

    public function getSchema(): SchemaInterface
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
