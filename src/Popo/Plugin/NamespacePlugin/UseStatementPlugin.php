<?php

declare(strict_types = 1);

namespace Popo\Plugin\NamespacePlugin;

use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpNamespace;
use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\NamespacePluginInterface;

class UseStatementPlugin implements NamespacePluginInterface
{

    public function run(BuilderPluginInterface $builder, PhpNamespace $namespace): PhpNamespace
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

        foreach ($builder->getSchema()->getConfig()->getUse() as $use) {
            $use = str_replace(';', '', $use);

            $tokens = [];
            preg_match('/(.*) as (.*)/', $use, $tokens);
            $constName = $tokens[1] ?? $use;
            $alias = $tokens[2] ?? null;
            
            $namespace->addUse((string)(new Literal($constName)), $alias, PhpNamespace::NameNormal);
        }

        return $namespace;
    }

}
