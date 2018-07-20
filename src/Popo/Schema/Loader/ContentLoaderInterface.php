<?php

declare(strict_types = 1);

namespace Popo\Schema\Loader;

use SplFileInfo;

interface ContentLoaderInterface
{
    /**
     * @param \SplFileInfo $filename
     *
     * @throws \Popo\Schema\Exception\SchemaLoaderException
     *
     * @return string
     */
    public function load(SplFileInfo $filename): string;
}
