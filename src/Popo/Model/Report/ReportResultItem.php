<?php

declare(strict_types = 1);

namespace Popo\Model\Report;

use Popo\PopoDefinesInterface;

class ReportResultItem
{
    protected string $name;
    protected string $type;
    protected string $schemaName = '$';
    protected string $popoName = '$';
    protected array $data = [];
    protected string $schemaFilename;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSchemaName(): ?string
    {
        return $this->schemaName;
    }

    public function setSchemaName(?string $schemaName): self
    {
        $this->schemaName = $schemaName;

        return $this;
    }

    public function getPopoName(): ?string
    {
        return $this->popoName;
    }

    public function setPopoName(?string $popoName): self
    {
        $this->popoName = $popoName;

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

    public function getSchemaFilename(): string
    {
        return $this->schemaFilename;
    }

    public function setSchemaFilename(string $schemaFilename): self
    {
        $this->schemaFilename = $schemaFilename;

        return $this;
    }

    public function markAsFileConfig(): self
    {
        $this->type = PopoDefinesInterface::VALIDATION_TYPE_FILE_CONFIG;

        return $this;
    }

    public function markAsSchemaConfig(): self
    {
        $this->type = PopoDefinesInterface::VALIDATION_TYPE_SCHEMA_CONFIG;

        return $this;
    }

    public function markAsPropertyConfig(): self
    {
        $this->type = PopoDefinesInterface::VALIDATION_TYPE_POPO_CONFIG;

        return $this;
    }
}
