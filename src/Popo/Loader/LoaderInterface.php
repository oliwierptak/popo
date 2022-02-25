<?php

declare(strict_types = 1);

namespace Popo\Loader;

use SplFileInfo;

interface LoaderInterface
{
    /**
     * @param \SplFileInfo $configurationFile
     *
     * @return array<string, mixed>
     */
    public function load(SplFileInfo $configurationFile): array;
}
