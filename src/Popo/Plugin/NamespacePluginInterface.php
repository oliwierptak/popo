<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use Nette\PhpGenerator\PhpNamespace;

interface NamespacePluginInterface
{
    public function run(PhpNamespace $namespace): PhpNamespace;
}
