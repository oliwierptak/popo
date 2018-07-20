<?php

declare(strict_types = 1);

namespace Popo\Finder;

use Symfony\Component\Finder\Finder;

class FinderFactory implements FinderFactoryInterface
{
    public function createFileLoader(): FileLoaderInterface
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
