<?php

declare(strict_types = 1);

namespace Popo\Schema\Loader;

class LoaderFactory implements LoaderFactoryInterface
{
    public function createContentLoader(): ContentLoaderInterface
    {
        return new ContentLoader();
    }

    public function createJsonLoader(): JsonLoaderInterface
    {
        return new JsonLoader();
    }
}
