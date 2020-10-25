<?php declare(strict_types = 1);

namespace TestsSuites\Popo\Functional;

use App\Popo\Case2\Foo;

class ReadmeCase2Test extends AbstractCaseTest
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
