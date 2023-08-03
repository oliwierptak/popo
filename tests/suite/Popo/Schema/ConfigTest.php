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
            ->setImplement('Foo\\FooInterface')
            ->setUse([
                'Some\BundleA\ClassA',
                'Some\BundleB\ClassB',
                'Some\BundleB\Interface',
            ])
            ->setAttribute('#[Foo(value)]')
            ->setAttributes(['Foo' => 'value']);

        $this->assertEquals(
            [
                'namespace' => 'Foo\\Bar',
                'namespaceRoot' => null,
                'outputPath' => '/tmp/test',
                'extend' => null,
                'implement' => 'Foo\\FooInterface',
                'comment' => 'Lorem Ipsum',
                'phpComment' => 'Lorem Ipsum PHP',
                'use' => [
                    'Some\BundleA\ClassA',
                    'Some\BundleB\ClassB',
                    'Some\BundleB\Interface',
                ],
                'trait' => [],
                'attribute' => '#[Foo(value)]',
                'attributes' => ['Foo' => 'value'],
                'classPluginCollection' => [],
                'phpFilePluginCollection' => [],
                'namespacePluginCollection' => [],
                'propertyPluginCollection' => [],
                'mappingPolicyPluginCollection' => [],
            ],
            $config->toArray(),
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
            'use' => ['A\B\Class', 'function foo'],
            'trait' => ['Some\Trait'],
            'attribute' => '#[Foo(value)]',
            'attributes' => ['Foo' => 'value'],
            'classPluginCollection' => ['classPluginCollection'],
            'phpFilePluginCollection' => ['phpFilePluginCollection'],
            'namespacePluginCollection' => ['namespacePluginCollection'],
            'propertyPluginCollection' => ['propertyPluginCollection'],
            'mappingPolicyPluginCollection' => ['mappingPolicyPluginCollection'],
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
                'use' => ['A\B\Class', 'function foo'],
                'trait' => ['Some\Trait'],
                'attribute' => '#[Foo(value)]',
                'attributes' => ['Foo' => 'value'],
                'classPluginCollection' => ['classPluginCollection'],
                'phpFilePluginCollection' => ['phpFilePluginCollection'],
                'namespacePluginCollection' => ['namespacePluginCollection'],
                'propertyPluginCollection' => ['propertyPluginCollection'],
                'mappingPolicyPluginCollection' => ['mappingPolicyPluginCollection'],
            ],
            $config->toArray(),
        );
    }
}
