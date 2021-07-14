<?php

declare(strict_types = 1);

namespace Popo;

class PopoResult
{
    protected array $generatedFiles = [];

    public function getGeneratedFiles(): array
    {
        return $this->generatedFiles;
    }

    public function setGeneratedFiles(array $generatedFiles): self
    {
        $this->generatedFiles = $generatedFiles;

        return $this;
    }

    public function add(string $file): self
    {
        $this->generatedFiles[] = $file;

        return $this;
    }
}
