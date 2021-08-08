<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Model\Generate\GenerateResult;
use Popo\Model\Report\ReportResult;

class PopoFacade implements PopoFacadeInterface
{
    protected PopoFactory $factory;

    protected function getFactory(): PopoFactory
    {
        if (empty($this->factory)) {
            $this->factory = new PopoFactory();
        }

        return $this->factory;
    }

    public function setFactory(PopoFactory $factory): self
    {
        $this->factory = $factory;

        return $this;
    }

    public function generate(PopoConfigurator $configurator): GenerateResult
    {
        return $this->getFactory()
            ->createPopoModel()
            ->generate($configurator);
    }

    public function report(PopoConfigurator $configurator): ReportResult
    {
        return $this->getFactory()
            ->createReportModel()
            ->generate($configurator);
    }
}
