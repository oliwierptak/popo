<?php

declare(strict_types = 1);

namespace Popo;

interface PopoFacadeInterface
{
    /**
     * Specification:
     * - Check if directories set in the configurator exist, throw exception on error.
     * - Generate POPO files based on schema.
     * - Save POPO files under location based on schema.
     *
     * @param \Popo\PopoConfigurator $configurator
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function generate(PopoConfigurator $configurator): void;
}
