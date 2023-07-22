<?php

declare(strict_types = 1);

namespace Popo\Plugin\NamespacePlugin;

use Nette\PhpGenerator\PhpNamespace;
use Popo\Plugin\NamespacePluginInterface;

class UseStatementPlugin implements NamespacePluginInterface
{

    public function run(PhpNamespace $namespace): PhpNamespace
    {
        $namespace->addUse('UnexpectedValueException');
        $namespace->addUse('DateTime');
        $namespace->addUse('DateTimeZone');
        $namespace->addUse('Throwable');
        $namespace->addUseFunction('array_filter');
        $namespace->addUseFunction('array_key_exists');
        $namespace->addUseFunction('array_keys');
        $namespace->addUseFunction('array_replace_recursive');
        $namespace->addUseFunction('in_array');
        $namespace->addUseFunction('sort');
        $namespace->addUseConstant('SORT_STRING');
        $namespace->addUseConstant('ARRAY_FILTER_USE_KEY');
        $namespace->addUseConstant('ARRAY_FILTER_USE_KEY');

        return $namespace;
    }

}
