<?php declare(strict_types = 1);

namespace App\UseTrait;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class UseTraitExamplePlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $builder->getClass()
            ->addMethod('useExample')
            ->setReturnType('int')
            ->setBody('return ExampleInterface::TEST_BUZZ;');
    }
}