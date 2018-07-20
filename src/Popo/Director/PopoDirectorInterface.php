<?php

declare(strict_types = 1);

namespace Popo\Director;

use Popo\Builder\BuilderConfiguratorInterface;

interface PopoDirectorInterface
{
    /**
     * Specification:
     * - Configures Data Transfer Object specific plugins in $configurator
     * - Generates Data Transfer Object files
     * - Generates Data Transfer Object Interface files
     * - Generates Data Transfer Object files according to $configurator specification
     *
     * @param \Popo\Builder\BuilderConfiguratorInterface $configurator
     *
     * @return void
     */
    public function generateDto(BuilderConfiguratorInterface $configurator): void;

    /**
     * Specification:
     * - Configures Plain Old Php Object specific plugins in $configurator
     * - Generates Plain Old Php Object files according to $configurator specification
     *
     * @param \Popo\Builder\BuilderConfiguratorInterface $configurator
     *
     * @return void
     */
    public function generatePopo(BuilderConfiguratorInterface $configurator): void;
}
