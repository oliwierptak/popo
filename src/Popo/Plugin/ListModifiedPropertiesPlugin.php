<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use Nette\PhpGenerator\ClassType;
use Popo\PluginInterface;
use Popo\Schema\Schema;

class ListModifiedPropertiesPlugin implements PluginInterface
{
    public function run(ClassType $class, Schema $schema): ClassType
    {
        $body = <<<EOF
return array_keys(\$this->updateMap);
EOF;

        $class
            ->addMethod('listModifiedProperties')
            ->setPublic()
            ->setReturnType('array')
            ->setBody($body);

        return $class;
    }
}
