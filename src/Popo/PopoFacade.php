<?php

declare(strict_types = 1);

namespace Popo;

class PopoFacade implements PopoFacadeInterfaces
{
    /**
     * @var \Popo\PopoFactory
     */
    protected $factory;

    public function setFactory(PopoFactory $factory): void
    {
        $this->factory = $factory;
    }

    protected function getFactory(): PopoFactory
    {
        if ($this->factory === null) {
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
