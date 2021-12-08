<?php

declare(strict_types = 1);

namespace Popo\Plugin;

use Popo\Schema\Property;

interface PropertyPluginInterface
{
    public function run(BuilderPluginInterface $builder, Property $property): void;
}
