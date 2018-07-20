<?php

declare(strict_types = 1);

namespace Popo\Schema\Loader;

interface LoaderFactoryInterface
{
    public function createContentLoader(): ContentLoaderInterface;

    public function createJsonLoader(): JsonLoaderInterface;
}
