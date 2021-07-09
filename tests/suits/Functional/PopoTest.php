<?php

declare(strict_types = 1);

namespace PopoTestsSuites\Functional;

use PHPUnit\Framework\TestCase;
use Popo\Schema\Config;

class PopoTest extends TestCase
{
    public function __test_Config_toArray(): void
    {
        $popo = (new Config)
            ->setSchemaPath('foobar');

        $this->assertEquals(
            [
                'schemaPath' => 'foobar',
                'templatePath' => null,
                'outputPath' => null,
                'namespace' => null,
            ],
            $popo->toArray()
        );
    }

    public function __test_Config_fromArray(): void
    {
        $popo = (new Config)
            ->fromArray(
                [
                    'schemaPath' => 'foobar',
                    'templatePath' => 'buzz',
                ]
            );

        $this->assertEquals(
            [
                'schemaPath' => 'foobar',
                'templatePath' => 'buzz',
                'outputPath' => '',
                'namespace' => '',
            ],
            $popo->toArray()
        );
    }
}
