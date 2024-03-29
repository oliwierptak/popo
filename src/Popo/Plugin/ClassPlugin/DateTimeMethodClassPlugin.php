<?php

declare(strict_types = 1);

namespace Popo\Plugin\ClassPlugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class DateTimeMethodClassPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $body = <<<EOF
if (self::METADATA[\$propertyName]['type'] === 'datetime' && \$this->\$propertyName === null) {
    \$value = self::METADATA[\$propertyName]['default'] ?: 'now';
    \$datetime = new DateTime(\$value);
    \$timezone = self::METADATA[\$propertyName]['timezone'] ?? null;
    if (\$timezone !== null) {
        \$timezone = new DateTimeZone(\$timezone);
        \$datetime = new DateTime(\$value, \$timezone);
    }    
    \$this->\$propertyName = \$datetime;
}
EOF;

        $builder->getClass()
            ->addMethod('setupDateTimeProperty')
            ->setProtected()
            ->setReturnType('void')
            ->setBody($body)
            ->addParameter('propertyName');
    }
}
