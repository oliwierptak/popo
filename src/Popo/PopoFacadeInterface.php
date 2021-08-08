<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Model\Generate\GenerateResult;

interface PopoFacadeInterface
{
    /**
     * Specification:
     * - Validate, throw exception in case of error.
     * - Generate POPO files based on schema.
     * - Create target directories based on output path and namespace.
     * - Save POPO files under location based on output path and namespace.
     * - Return instance of GenerateResult
     *
     * @param \Popo\PopoConfigurator $configurator
     *
     * @return \Popo\Model\Generate\GenerateResult
     * @throws \InvalidArgumentException
     */
    public function generate(PopoConfigurator $configurator): GenerateResult;
}
