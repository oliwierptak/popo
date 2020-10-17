<?php

declare(strict_types = 1);

namespace Popo;

use Popo\Builder\BuilderConfigurator;
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

    public function generateDto(BuilderConfigurator $configurator): void
    {
        $this->getFactory()
            ->createPopoDirector()
            ->generateDto($configurator);
    }

    public function generatePopo(BuilderConfigurator $configurator): int
    {
        return $this->getFactory()
            ->createPopoDirector()
            ->generatePopo($configurator);
    }

    public function generatePopoString(BuilderConfigurator $configurator, SchemaInterface $schema): string
    {
        return $this->getFactory()
            ->createStringDirector()
            ->generatePopoString($configurator, $schema);
    }

    public function generateDtoString(BuilderConfigurator $configurator, SchemaInterface $schema): string
    {
        return $this->getFactory()
            ->createStringDirector()
            ->generateDtoString($configurator, $schema);
    }

    public function generateDtoInterfaceString(BuilderConfigurator $configurator, SchemaInterface $schema): string
    {
        return $this->getFactory()
            ->createStringDirector()
            ->generateDtoInterfaceString($configurator, $schema);
    }
}
