<?php

declare(strict_types = 1);

namespace Popo\Model\Generate;

class GenerateResult
{
    /**
     * @var array<array{filename: string, schemaName: string, popoName: string, namespace: string}>
     */
    protected array $generatedFiles = [];

    /**
     * @return array<array{filename: string, schemaName: string, popoName: string, namespace: string}>
     */
    public function getGeneratedFiles(): array
    {
        return $this->generatedFiles;
    }

    /**
     * @param array{filename: string, schemaName: string, popoName: string, namespace: string} $item
     *
     * @return $this
     */
    public function add(array $item): self
    {
        $this->generatedFiles[] = $item;

        return $this;
    }
}
