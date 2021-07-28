<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Model\PopoGenerateResult;

interface PopoFacadeInterface
{
    /**
     * Specification:
     * - Check if directories set in the configurator exist, creat them if needed.
     * - Generate POPO files based on schema.
     * - Save POPO files under location based on schema path and namespace.
     * - Return instance of PopoGenerateResult
     *
     * @param \Popo\PopoConfigurator $configurator
     *
     * @return \Popo\Model\PopoGenerateResult
     * @throws \InvalidArgumentException
     */
    public function generate(PopoConfigurator $configurator): PopoGenerateResult;
}
