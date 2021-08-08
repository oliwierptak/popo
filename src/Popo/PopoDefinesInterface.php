<?php

declare(strict_types = 1);

namespace Popo;

use JetBrains\PhpStorm\ArrayShape;

class PopoDefinesInterface
{
    public const VERSION = 5;

    public const VALIDATION_TYPE_FILE_CONFIG = 'file-config';

    public const VALIDATION_TYPE_SCHEMA_CONFIG = 'schema-config';

    public const VALIDATION_TYPE_PROPERTY_CONFIG = 'property-config';

    public const CONFIGURATION_SCHEMA_OPTION_SYMBOL = '$';

    public const CONFIGURATION_SCHEMA_CONFIG = 'config';

    public const CONFIGURATION_SCHEMA_PROPERTY = 'property';

    public const CONFIGURATION_SCHEMA_DEFAULT = 'default';

    public const CONFIGURATION_SCHEMA_PROPERTY_NAME = 'name';

    public const SCHEMA_PROPERTY_DEFAULT = 'default';

    public const SCHEMA_PROPERTY_TYPE = 'type';

    //property types
    public const PROPERTY_TYPE_ARRAY = 'array';

    public const PROPERTY_TYPE_BOOL = 'bool';

    public const PROPERTY_TYPE_FLOAT = 'float';

    public const PROPERTY_TYPE_INT = 'int';

    public const PROPERTY_TYPE_STRING = 'string';

    public const PROPERTY_TYPE_MIXED = 'mixed';

    public const PROPERTY_TYPE_CONST = 'const';

    public const PROPERTY_TYPE_POPO = 'popo';

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

    public const SCHEMA_CONFIGURATION_DEFAULT_DATA = [
        'namespace' => 'Popo',
        'outputPath' => '/tmp',
        'namespaceRoot' => null,
        'extend' => null,
        'implement' => null,
        'comment' => null,
    ];

    public const SCHEMA_PROPERTY_DEFAULT_DATA = [
        'name' => null,
        'type' => self::PROPERTY_TYPE_STRING,
        'comment' => null,
        'itemType' => null,
        'itemName' => null,
        'default' => null,
    ];
}
