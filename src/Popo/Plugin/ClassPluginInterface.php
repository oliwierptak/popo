<?php

declare(strict_types = 1);

namespace Popo\Plugin;

interface ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void;
}
