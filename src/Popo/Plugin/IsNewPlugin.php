<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use Nette\PhpGenerator\ClassType;
use Popo\PluginInterface;
use Popo\Schema\Schema;

class IsNewPlugin implements PluginInterface
{
    public function run(ClassType $class, Schema $schema): ClassType
    {
        $body = <<<EOF
return empty(\$this->updateMap) === true;
EOF;

        $class
            ->addMethod('isNew')
            ->setPublic()
            ->setReturnType('bool')
            ->setBody($body);

        return $class;
    }
}
