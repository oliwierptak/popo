<?php

declare(strict_types = 1);

namespace Popo\Model\Generate;

use JetBrains\PhpStorm\ArrayShape;

class GenerateResult
{
    protected const ITEM_SHAPE = [
        'filename' => 'string',
        'schemaName' => 'string',
        'popoName' => 'string',
        'namespace' => 'string',
    ];

    #[ArrayShape(self::ITEM_SHAPE)]
    protected array $generatedFiles = [];

    #[ArrayShape(self::ITEM_SHAPE)]
    public function getGeneratedFiles(): array
    {
        return $this->generatedFiles;
    }

    public function setGeneratedFiles(#[ArrayShape(self::ITEM_SHAPE)] array $generatedFiles): self
    {
        $this->generatedFiles = $generatedFiles;

        return $this;
    }

    public function add(#[ArrayShape(self::ITEM_SHAPE)] array $item): self
    {
        $this->generatedFiles[] = $item;

        return $this;
    }
}
