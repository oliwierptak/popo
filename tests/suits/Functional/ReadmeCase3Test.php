<?php declare(strict_types = 1);

namespace TestsSuites\Popo\Functional;

use App\Popo\Case3\Foo;

class ReadmeCase3Test extends AbstractCaseTest
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
