<?php

declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Schema\Reader\SchemaInterface;

interface GeneratorInterface
{
    public function generate(SchemaInterface $schema): string;
}
