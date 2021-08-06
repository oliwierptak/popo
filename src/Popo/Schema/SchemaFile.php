<?php

declare(strict_types = 1);

namespace Popo\Schema;

use SplFileInfo;

class SchemaFile
{
    protected SplFileInfo $filename;
    protected array $fileConfig = [];
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

    public function getFileConfig(): array
    {
        return $this->fileConfig;
    }

    public function setFileConfig(array $fileConfig): self
    {
        $this->fileConfig = $fileConfig;

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
