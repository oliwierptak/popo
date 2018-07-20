<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Builder\BuilderConfiguratorInterface;
use Popo\Schema\Reader\SchemaInterface;

class PopoFacade implements PopoFacadeInterfaces
{
    /**
     * @var \Popo\PopoFactoryInterface
     */
    protected $factory;

    public function setFactory(PopoFactoryInterface $factory): void
    {
        $this->factory = $factory;
    }

    protected function getFactory(): PopoFactoryInterface
    {
        if ($this->factory === null) {
            $this->factory = new PopoFactory();
        }

        return $this->factory;
    }

    public function generateDto(BuilderConfiguratorInterface $configurator): void
    {
        $this->getFactory()
            ->createPopoDirector()
            ->generateDto($configurator);
    }

    public function generatePopo(BuilderConfiguratorInterface $configurator): void
    {
        $this->getFactory()
            ->createPopoDirector()
            ->generatePopo($configurator);
    }

    public function generatePopoString(BuilderConfiguratorInterface $configurator, SchemaInterface $schema): string
    {
        return $this->getFactory()
            ->createStringDirector()
            ->generatePopoString($configurator, $schema);
    }

    public function generateDtoString(BuilderConfiguratorInterface $configurator, SchemaInterface $schema): string
    {
        return $this->getFactory()
            ->createStringDirector()
            ->generateDtoString($configurator, $schema);
    }

    public function generateDtoInterfaceString(BuilderConfiguratorInterface $configurator, SchemaInterface $schema): string
    {
        return $this->getFactory()
            ->createStringDirector()
            ->generateDtoInterfaceString($configurator, $schema);
    }
}
