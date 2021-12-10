<?php

namespace PopoTestSuite;

use Popo\PopoConfigurator;
use PHPUnit\Framework\TestCase;

class PopoConfiguratorTest extends TestCase
{
    public function test_set_schema_filename()
    {
        $configurator = (new PopoConfigurator)->setSchemaFilenameMask('*.schema.yml');

        $this->assertEquals('*.schema.yml', $configurator->getSchemaFilenameMask());
    }
}
