<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use Nette\PhpGenerator\ClassType;
use Popo\PluginInterface;
use Popo\Schema\Schema;

class RequireAllPlugin implements PluginInterface
{
    public function run(ClassType $class, Schema $schema): ClassType
    {
        $body = <<<EOF
\$errors = [];

%s

if (empty(\$errors) === false) {
    throw new UnexpectedValueException(
        implode("\\n", \$errors)
    );
}

return \$this;
EOF;

        $validationBody = <<<EOF
try {
    \$this->require%s();
}
catch (\Throwable \$throwable) {
    \$errors['%s'] = \$throwable->getMessage();
}

EOF;

        $require = '';
        foreach ($schema->getPropertyCollection() as $property) {
            $require .= sprintf(
                $validationBody,
                ucfirst($property->getName()),
                $property->getName()
            );
        }

        $body = sprintf(
            $body,
            rtrim($require, "\n")
        );

        $class
            ->addMethod('requireAll')
            ->setPublic()
            ->setReturnType('self')
            ->setBody($body);

        return $class;
    }
}
