<?php

declare(strict_types = 1);

namespace Popo;

use Nette\PhpGenerator\ClassType;
use Popo\Schema\Schema;

interface PluginInterface
{
    public function run(ClassType $class, Schema $schema): ClassType;
}
