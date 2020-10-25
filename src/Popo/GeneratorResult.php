<?php declare(strict_types = 1);

namespace Popo;

class GeneratorResult
{
    protected int $fileCount = 0;

    public function getFileCount(): int
    {
        return $this->fileCount;
    }

    public function setFileCount(int $fileCount): self
    {
        $this->fileCount = $fileCount;

        return $this;
    }

    public function grow(): void
    {
        $this->fileCount++;
    }
}
