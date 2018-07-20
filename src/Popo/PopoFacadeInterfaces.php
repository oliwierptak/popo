<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Builder\BuilderConfiguratorInterface;
use Popo\Schema\Reader\SchemaInterface;

interface PopoFacadeInterfaces
{
    public function setFactory(PopoFactoryInterface $factory): void;

    /**
     * Specification:
     * - Configures DTO plugins in $configurator
     * - Adds 'Interface' prefix to $configurator's file extension
     * - Sets SchemaBuilderConfigurator's schema template filename to 'interface/php.interface.schema.tpl'
     * - Sets SchemaBuilderConfigurator's property template filename to 'interface/php.interface.property.tpl'
     * - Generates Data Transfer Object files according to $configurator specification
     * - Generates Data Transfer Object Interface files according to $configurator specification
     *
     * @param \Popo\Builder\BuilderConfiguratorInterface $configurator
     *
     * @return void
     */
    public function generateDto(BuilderConfiguratorInterface $configurator): void;

    /**
     * Specification:
     * - Configures POPO plugins in $configurator
     * - Generates Plain Old Php Object files according to $configurator specification
     *
     * @param \Popo\Builder\BuilderConfiguratorInterface $configurator
     *
     * @return void
     */
    public function generatePopo(BuilderConfiguratorInterface $configurator): void;

    /**
     * Specification:
     * - Configures schema name
     * - Configures POPO plugins in $configurator
     * - Generates string based on $schema
     * - Returns generated string
     *
     * @param \Popo\Builder\BuilderConfiguratorInterface $configurator
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     *
     * @return string
     */
    public function generatePopoString(BuilderConfiguratorInterface $configurator, SchemaInterface $schema): string;

    public function generateDtoString(BuilderConfiguratorInterface $configurator, SchemaInterface $schema): string;

    public function generateDtoInterfaceString(BuilderConfiguratorInterface $configurator, SchemaInterface $schema): string;
}
