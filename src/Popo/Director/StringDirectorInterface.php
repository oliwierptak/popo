<?php

declare(strict_types = 1);

namespace Popo\Director;

use Popo\Builder\BuilderConfiguratorInterface;
use Popo\Schema\Reader\SchemaInterface;

interface StringDirectorInterface
{
    public function generateDtoString(BuilderConfiguratorInterface $configurator, SchemaInterface $schema): string;

    public function generateDtoInterfaceString(BuilderConfiguratorInterface $configurator, SchemaInterface $schema): string;

    public function generatePopoString(BuilderConfiguratorInterface $configurator, SchemaInterface $schema): string;
}
