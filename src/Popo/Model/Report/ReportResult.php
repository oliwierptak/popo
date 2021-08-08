<?php

declare(strict_types = 1);

namespace Popo\Model\Report;

class ReportResult
{
    /**
     * @var \Popo\Model\Report\ReportResultItem[]
     */
    protected array $data = [];

    /**
     * @return \Popo\Model\Report\ReportResultItem[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param \Popo\Model\Report\ReportResultItem[] $data
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
