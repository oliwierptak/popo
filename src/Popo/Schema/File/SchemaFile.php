<?php

declare(strict_types = 1);

namespace Popo\Schema\File;

use SplFileInfo;

class SchemaFile
{
    protected SplFileInfo $filename;
    /**
     * @var array<string, mixed>
     */
    protected array $fileConfig = [];
    /**
     * @var array<string, mixed>
     */
    protected array $schemaConfig = [];
    /**
     * @var array<string, mixed>
     */
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

    /**
     * @return array<string, mixed>
     */
    public function getFileConfig(): array
    {
        return $this->fileConfig;
    }

    /**
     * @param array<string, mixed> $fileConfig
     *
     * @return $this
     */
    public function setFileConfig(array $fileConfig): self
    {
        $this->fileConfig = $fileConfig;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSchemaConfig(): array
    {
        return $this->schemaConfig;
    }

    /**
     * @param array<string, mixed> $schemaConfig
     *
     * @return $this
     */
    public function setSchemaConfig(array $schemaConfig): self
    {
        $this->schemaConfig = $schemaConfig;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
