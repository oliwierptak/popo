<?php

declare(strict_types = 1);

namespace Popo\Schema;

use SplFileInfo;

class SchemaFile
{
    protected SplFileInfo $filename;
    protected array $sharedConfig = [];
    protected array $schemaConfig = [];
    protected array $data = [];

    public function getFilename(): SplFileInfo
    {
        return $this->filename;
    }

    public function setFilename(SplFileInfo $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getSharedConfig(): array
    {
        return $this->sharedConfig;
    }

    public function setSharedConfig(array $sharedConfig): self
    {
        $this->sharedConfig = $sharedConfig;

        return $this;
    }

    public function getSchemaConfig(): array
    {
        return $this->schemaConfig;
    }

    public function setSchemaConfig(array $schemaConfig): self
    {
        $this->schemaConfig = $schemaConfig;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
