<?php

namespace Popo\Generator;

use Popo\Schema\Reader\Schema;

interface GeneratorInterface
{
    public function generate(Schema $schema): string;
}
