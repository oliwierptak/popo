<?php

declare(strict_types = 1);

namespace Popo;

interface PopoFacadeInterface
{
    /**
     * Specification:
     * - Check if directories set in the configurator exist, creat them if needed.
     * - Generate POPO files based on schema.
     * - Save POPO files under location based on schema path and namespace.
     * - Return instance of PopoResult
     *
     * @param \Popo\PopoConfigurator $configurator
     *
     * @return \Popo\PopoResult
     * @throws \InvalidArgumentException
     */
    public function generate(PopoConfigurator $configurator): PopoResult;
}
