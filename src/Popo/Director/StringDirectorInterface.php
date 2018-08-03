<?php

declare(strict_types = 1);

namespace Popo\Director;

use Popo\Builder\BuilderConfigurator;
use Popo\Schema\Reader\SchemaInterface;

interface StringDirectorInterface
{
    public function generateDtoString(BuilderConfigurator $configurator, SchemaInterface $schema): string;

    public function generateDtoInterfaceString(BuilderConfigurator $configurator, SchemaInterface $schema): string;

    public function generatePopoString(BuilderConfigurator $configurator, SchemaInterface $schema): string;
}
