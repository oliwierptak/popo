<?php

declare(strict_types = 1);

namespace Popo\Finder;

use Symfony\Component\Finder\Finder;

class FinderFactory
{
    public function createFileLoader(): FileLoader
    {
        return new FileLoader(
            $this->createFinder()
        );
    }

    public function createFinder(): Finder
    {
        return Finder::create();
    }
}
