<?php

declare(strict_types = 1);

namespace Popo\Director;

use Popo\Builder\BuilderConfigurator;

interface PopoDirectorInterface
{
    /**
     * Specification:
     * - Configures Data Transfer Object specific plugins in $configurator
     * - Generates Data Transfer Object files
     * - Generates Data Transfer Object Interface files
     * - Generates Data Transfer Object files according to $configurator specification
     *
     * @param \Popo\Builder\BuilderConfigurator $configurator
     *
     * @return void
     */
    public function generateDto(BuilderConfigurator $configurator): void;

    /**
     * Specification:
     * - Configures Value Object specific plugins in $configurator
     * - Generates Value Object files according to $configurator specification
     *
     * @param \Popo\Builder\BuilderConfigurator $configurator
     *
     * @return void
     */
    public function generatePopo(BuilderConfigurator $configurator): void;
}
