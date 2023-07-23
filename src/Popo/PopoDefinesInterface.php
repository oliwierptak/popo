<?php

declare(strict_types = 1);

namespace Popo;

class PopoDefinesInterface
{
    public const VERSION = 6;

    public const VALIDATION_TYPE_FILE_CONFIG = 'file-config';

    public const VALIDATION_TYPE_SCHEMA_CONFIG = 'schema-config';

    public const VALIDATION_TYPE_POPO_CONFIG = 'popo-config';

    public const CONFIGURATION_SCHEMA_OPTION_SYMBOL = '$';

    public const CONFIGURATION_SCHEMA_CONFIG = 'config';

    public const CONFIGURATION_SCHEMA_PROPERTY = 'property';

    public const CONFIGURATION_SCHEMA_DEFAULT = 'default';

    public const CONFIGURATION_SCHEMA_PROPERTY_NAME = 'name';

    public const SCHEMA_PROPERTY_DEFAULT = 'default';

    public const SCHEMA_PROPERTY_TYPE = 'type';

    public const SCHEMA_PROPERTY_EXTRA = 'extra';

    public const SCHEMA_PROPERTY_ATTRIBUTE = 'attribute';

    public const SCHEMA_PROPERTY_MAPPING_POLICY = 'mappingPolicy';

    public const SCHEMA_PROPERTY_MAPPING_POLICY_VALUE = 'mappingPolicyValue';

    public const PROPERTY_TYPE_ARRAY = 'array';

    public const PROPERTY_TYPE_BOOL = 'bool';

    public const PROPERTY_TYPE_FLOAT = 'float';

    public const PROPERTY_TYPE_INT = 'int';

    public const PROPERTY_TYPE_STRING = 'string';

    public const PROPERTY_TYPE_MIXED = 'mixed';

    public const PROPERTY_TYPE_CONST = 'const';

    public const PROPERTY_TYPE_POPO = 'popo';

    public const PROPERTY_TYPE_DATETIME = 'datetime';

    public const PROPERTY_TYPE_EXTRA_TIMEZONE = 'timezone';

    public const PROPERTY_TYPE_EXTRA_FORMAT = 'format';

    public const SCHEMA_KEYS = [
        PopoDefinesInterface::CONFIGURATION_SCHEMA_CONFIG,
        PopoDefinesInterface::CONFIGURATION_SCHEMA_DEFAULT,
        PopoDefinesInterface::CONFIGURATION_SCHEMA_PROPERTY,
    ];

    public const SCHEMA_DEFAULT_DATA = [
        'config' => self::SCHEMA_CONFIGURATION_DEFAULT_DATA,
        'default' => [],
        'property' => [],
    ];

    /**
     * @var array{namespace: string, outputPath: string, namespaceRoot: string|null, extend: string|null, implement: string|null, comment:string|null, phpComment: string|null, attribute: string|null, attributes: array}
     */
    public const SCHEMA_CONFIGURATION_DEFAULT_DATA = [
        'namespace' => 'Popo',
        'outputPath' => null,
        'namespaceRoot' => null,
        'extend' => null,
        'implement' => null,
        'comment' => null,
        'phpComment' => null,
        'attribute' => null,
        'attributes' => [],
    ];

    /**
     * @var array{name: string, type: string, comment: string|null, itemType: string|null, itemName: string|null, default: mixed, extra: mixed, attribute: string|null, attributes: array, mappingPolicy: array, mappingPolicyValue: string|null}
     */
    public const SCHEMA_PROPERTY_DEFAULT_DATA = [
        'name' => null,
        'type' => self::PROPERTY_TYPE_STRING,
        'comment' => null,
        'itemType' => null,
        'itemName' => null,
        'default' => null,
        'extra' => null,
        'attribute' => null,
        'attributes' => [],
        'mappingPolicy' => ['\Popo\Plugin\MappingPolicy\NoneMappingPolicyPlugin::MAPPING_POLICY_NAME'],
        'mappingPolicyValue' => null,
    ];
}
