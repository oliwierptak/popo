<?php

declare(strict_types = 1);

namespace Popo\Model\Report;

class ReportResult
{
    /**
     * @var array<string, array<\Popo\Model\Report\ReportResultItem>>
     */
    protected array $data = [];

    /**
     * @return array<string, array<\Popo\Model\Report\ReportResultItem>>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array<string, array<\Popo\Model\Report\ReportResultItem>> $data
     *
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function add(ReportResultItem $item): self
    {
        $this->data[$item->getName()][] = $item;

        return $this;
    }
}
