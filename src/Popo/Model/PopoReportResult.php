<?php

declare(strict_types = 1);

namespace Popo\Model;

use JetBrains\PhpStorm\ArrayShape;

class PopoReportResult
{
    protected array $data = [];

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
