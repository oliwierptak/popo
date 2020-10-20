<?php

declare(strict_types = 1);

namespace Popo;

class GeneratorResult
{
    protected int $fileCount;

    public function getFileCount(): int
    {
        return $this->fileCount;
    }

    public function setFileCount(int $fileCount): self
    {
        $this->fileCount = $fileCount;

        return $this;
    }
}
