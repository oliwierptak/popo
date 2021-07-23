<?php

declare(strict_types = 1);

namespace Popo\Loader;

use SplFileInfo;

interface LoaderInterface
{
    public function load(SplFileInfo $configurationFile): array;
}
