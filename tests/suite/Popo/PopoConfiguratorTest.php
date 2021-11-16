<?php

namespace PopoTestSuite;

use Popo\PopoConfigurator;
use PHPUnit\Framework\TestCase;

class PopoConfiguratorTest extends TestCase
{
    public function testSetSchemaFilename()
    {
        $configurator = (new PopoConfigurator)->setSchemaFilenameMask('*.schema.yml');

        $this->assertEquals('*.schema.yml', $configurator->getSchemaFilenameMask());
    }
}
