<?php

declare(strict_types = 1);

namespace Popo\Model;

use Popo\Builder\SchemaBuilder;
use Popo\PopoConfigurator;

class ReportModel
{
    public function __construct(
        protected SchemaBuilder $builder
    ) {
    }

    public function generate(PopoConfigurator $configurator): PopoReportResult
    {
        $result = new PopoReportResult;
        $data = $this->builder->generateSchemaReport($configurator);

        $result->setData($data);

        return $result;
    }
}
