<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class RequireAllClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
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
        foreach ($builder->getSchema()->getPropertyCollection() as $property) {
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

        $builder->getClass()
            ->addMethod('requireAll')
            ->setPublic()
            ->setReturnType('self')
            ->setBody($body);
    }
}
