<?php

declare(strict_types = 1);

namespace Popo\Model\Generate;

class GenerateResult
{
    protected const ITEM_SHAPE = [
        'filename' => 'string',
        'schemaName' => 'string',
        'popoName' => 'string',
        'namespace' => 'string',
    ];

    protected array $generatedFiles = [];

    public function getGeneratedFiles(): array
    {
        return $this->generatedFiles;
    }

    public function add(array $item): self
    {
        $this->generatedFiles[] = $item;

        return $this;
    }
}
