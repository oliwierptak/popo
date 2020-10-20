<?php

declare(strict_types = 1);

namespace Popo;

class PopoFacade implements PopoFacadeInterfaces
{
    protected ?PopoFactory $factory;

    public function setFactory(PopoFactory $factory): void
    {
        $this->factory = $factory;
    }

    protected function getFactory(): PopoFactory
    {
        if (empty($this->factory)) {
            $this->factory = new PopoFactory();
        }

        return $this->factory;
    }

    public function generate(Configurator $configurator): GeneratorResult
    {
        return $this->getFactory()
            ->createPopoModel($configurator)
            ->generate($configurator);
    }
}
