<?php

declare(strict_types = 1);

namespace Popo\Plugin\NamespacePlugin;

use Nette\PhpGenerator\PhpNamespace;
use Popo\Plugin\NamespacePluginInterface;

class UseUnexpectedValueExceptionPlugin implements NamespacePluginInterface
{

    public function run(PhpNamespace $namespace): PhpNamespace
    {
        $namespace->addUse('UnexpectedValueException');

        return $namespace;
    }

}
