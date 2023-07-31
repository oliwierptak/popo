<?php

declare(strict_types = 1);

namespace PopoTestSuite\Schema;

use PHPUnit\Framework\TestCase;
use Popo\Schema\Inspector\SchemaInspector;
use Popo\Schema\Validator\Definition\ConfigDefinition;
use Popo\Schema\Validator\Definition\DefaultDefinition;
use Popo\Schema\Validator\Definition\PropertyDefinition;
use Popo\Schema\Validator\Validator;

/**
 * @group unit
 */
class ValidatorTest extends TestCase
{
    public function test_validate(): void
    {
        $validator = new Validator([
            DefaultDefinition::ALIAS => new DefaultDefinition(),
            ConfigDefinition::ALIAS => new ConfigDefinition(),
            PropertyDefinition::ALIAS => new PropertyDefinition(
                new SchemaInspector()
            ),
        ]);

        $input = [
            'config' => [
                'namespace' => 'namespace',
                'outputPath' => 'outputPath',
                'namespaceRoot' => 'namespaceRoot',
                'extend' => 'extend',
                'implement' => 'implement',
                'comment' => 'comment',
                'phpComment' => 'phpComment',
                'use' => ['use'],
                'attribute' => 'attribute',
                'attributes' => [[
                    'name' => 'name',
                    'value' => 'value',
                ]],
            ],
            'property' => [[
                'name' => 'name',
                'type' => 'type',
                'comment' => 'comment',
                'default' => 'default',
                'itemType' => 'itemType',
                'itemName' => 'itemName',
                'mappingPolicyValue' => 'mappingPolicyValue',
                'attribute' => 'attribute',
                'attributes' => [[
                    'name' => 'name',
                    'value' => 'value',
                ]],
                'extra' => [
                    'timezone' => 'timezone',
                    'format' => 'format',
                ],
                'mappingPolicy' => [
                    'none', 'lower',
                ],
            ]],
            'default' => [],
        ];

        $result = $validator->validate($input);

        $this->assertEquals($input, $result);
    }
}
