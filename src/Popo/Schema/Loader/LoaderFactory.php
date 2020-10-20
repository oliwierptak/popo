<?php

declare(strict_types = 1);

namespace Popo\Schema\Loader;

class LoaderFactory
{
    public function createContentLoader(): ContentLoader
    {
        return new ContentLoader();
    }

    public function createJsonLoader(): JsonLoader
    {
        return new JsonLoader();
    }
}
