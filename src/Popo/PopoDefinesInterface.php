<?php

declare(strict_types = 1);

namespace Popo;

class PopoDefinesInterface
{
    public const VERSION = 5;

    public const CONFIGURATION_SCHEMA_OPTION = '$';

    public const CONFIGURATION_SCHEMA_CONFIG = 'config';

    public const CONFIGURATION_SCHEMA_PROPERTY = 'property';

    public const CONFIGURATION_SCHEMA_DEFAULT = 'default';

    public const CONFIGURATION_SCHEMA_DATA = 'data';

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
}
