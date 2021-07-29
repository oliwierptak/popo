<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Model\PopoGenerateResult;

interface PopoFacadeInterface
{
    /**
     * Specification:
     * - Validate, throw exception in case of error.
     * - Generate POPO files based on schema.
     * - Create target directories based on output path and namespace.
     * - Save POPO files under location based on output path and namespace.
     * - Return instance of PopoGenerateResult
     *
     * @param \Popo\PopoConfigurator $configurator
     *
     * @return \Popo\Model\PopoGenerateResult
     * @throws \InvalidArgumentException
     */
    public function generate(PopoConfigurator $configurator): PopoGenerateResult;
}
