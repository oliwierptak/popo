<?php

declare(strict_types = 1);

namespace Popo;

interface PopoFacadeInterfaces
{
    public const VERSION = '3.0.0';

    public function generate(Configurator $configurator): GeneratorResult;
}
