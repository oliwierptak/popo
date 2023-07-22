<?php

declare(strict_types = 1);

namespace PopoTestSuite\Schema;

use PHPUnit\Framework\TestCase;
use Popo\Schema\Config\Config;

/**
 * @group unit
 */
class ConfigTest extends TestCase
{
    public function test_array_partial(): void
    {
        $config = (new Config)
            ->setNamespace('Foo\\Bar')
            ->setOutputPath('/tmp/test')
            ->setComment('Lorem Ipsum')
            ->setPhpComment('Lorem Ipsum PHP')
            ->setImplement('Foo\\FooInterface');


        $this->assertEquals(
            [
                'namespace' => 'Foo\\Bar',
                'namespaceRoot' => null,
                'outputPath' => '/tmp/test',
                'extend' => null,
                'implement' => 'Foo\\FooInterface',
                'comment' => 'Lorem Ipsum',
                'phpComment' => 'Lorem Ipsum PHP',
            ],
            $config->toArray()
        );
    }

    public function test_array_full(): void
    {
        $config = (new Config)->fromArray([
            'namespace' => 'Foo\\Bar',
            'namespaceRoot' => 'Foo',
            'outputPath' => '/tmp/test',
            'extend' => 'Foo\\Buzz',
            'implement' => 'Foo\\FooInterface',
            'comment' => 'This is a comment',
            'phpComment' => 'This is a PHP comment',
        ]);

        $this->assertEquals(
            [
                'namespace' => 'Foo\\Bar',
                'namespaceRoot' => 'Foo',
                'outputPath' => '/tmp/test',
                'extend' => 'Foo\\Buzz',
                'implement' => 'Foo\\FooInterface',
                'comment' => 'This is a comment',
                'phpComment' => 'This is a PHP comment',
            ],
            $config->toArray()
        );
    }
}
