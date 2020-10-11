<?php

declare(strict_types = 1);

namespace Popo\Generator;

use Popo\Builder\BuilderContainer;

interface GeneratorFactoryInterface
{
    public function createSchemaGenerator(BuilderContainer $container): GeneratorInterface;

    public function createPropertyGenerator(BuilderContainer $container): GeneratorInterface;

    public function createArrayableGenerator(BuilderContainer $container): GeneratorInterface;

    public function createCollectionGenerator(BuilderContainer $container): GeneratorInterface;
}
