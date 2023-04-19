<?php

declare(strict_types = 1);

namespace PopoTestPerformance\ToArray\Fake;

class ToArrayFake
{
    protected const METADATA = [
        'idForAll' => [
            'type' => 'int',
            'default' => 30,
            'remap' => 'idForAll',
            'mappingPolicy' => 0,
            'mappingPolicyCollection' => [
                0 => 'idForAll',
                1 => 'idforall',
                2 => 'IDFORALL',
                3 => 'id_for_all',
                4 => 'idForAll',
            ],
        ],
        'idFromExampleSchema' => [
            'type' => 'int',
            'default' => 20,
            'remap' => 'idFromExampleSchema',
            'mappingPolicy' => 0,
            'mappingPolicyCollection' => [
                0 => 'idFromExampleSchema',
                1 => 'idfromexampleschema',
                2 => 'IDFROMEXAMPLESCHEMA',
                3 => 'id_from_example_schema',
                4 => 'idFromExampleSchema',
            ],
        ],
        'fooId' => [
            'type' => 'int',
            'default' => null,
            'remap' => 'FOO_ID',
            'mappingPolicy' => 3,
            'mappingPolicyCollection' => [
                0 => 'fooId',
                1 => 'fooid',
                2 => 'FOOID',
                3 => 'foo_id',
                4 => 'fooId',
            ],
        ],
        'title' => [
            'type' => 'string',
            'default' => 'Example Foo Hakuna Matata',
            'remap' => 'title',
            'mappingPolicy' => 0,
            'mappingPolicyCollection' => [
                0 => 'title',
                1 => 'title',
                2 => 'TITLE',
                3 => 'title',
                4 => 'title',
            ],
        ],
        'value' => [
            'type' => 'int',
            'default' => \App\ExampleInterface::TEST_BUZZ,
            'remap' => 'value',
            'mappingPolicy' => 0,
            'mappingPolicyCollection' => [
                0 => 'value',
                1 => 'value',
                2 => 'VALUE',
                3 => 'value',
                4 => 'value',
            ],
        ],
    ];

    protected array $updateMap = [];
    protected ?int $idForAll = 30;
    protected ?int $idFromExampleSchema = 20;
    protected ?int $fooId = null;
    protected ?string $title = 'Example Foo Hakuna Matata';
    protected ?int $value = \App\ExampleInterface::TEST_BUZZ;
}
