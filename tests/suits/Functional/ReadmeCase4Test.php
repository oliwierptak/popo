<?php declare(strict_types = 1);

namespace TestsSuites\Popo\Functional;

use App\Popo\Case4\Foo;

class ReadmeCase4Test extends AbstractCaseTest
{
    protected function getPopoToTest(): object
    {
        $foo = new Foo();

        return $foo;
    }

    protected function getPopoToTestClassName(): string
    {
        return Foo::class;
    }
}
