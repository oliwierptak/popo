<?php

namespace PopoTestSuite\Plugin;

use LogicException;
use PHPUnit\Framework\TestCase;
use Popo\Plugin\PluginContainer;
use Popo\PopoConfigurator;

class PluginContainerTest extends TestCase
{
    public function test_createMappingPolicyPlugins_should_throw_exception_when_wrong_class(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Invalid plugin class name: FoobarInvalid::class');

        $configurator = (new PopoConfigurator())
            ->setMappingPolicyPluginCollection([])
            ->addMappingPolicyPluginClass('FoobarInvalid::class');

        (new PluginContainer($configurator))->createMappingPolicyPlugins();
    }
}
