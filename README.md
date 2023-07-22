# POPO

POPO - "Plain Old Php Object" was inspired by "Plain Old Java Object" (POJO) concept.

POPO generator can also locate, load, validate, and combine POPO schemas to create PHP source code files, representing
Data Structures / Data Transfer Objects.

The schema supports key mapping, inheritance, collections and encapsulation of other POPO objects.

### Example

Simple schema in YAML format, describing properties and relations of POPO objects.

In this case, `Foo` uses `Bar` as its dependency, and they are both defined under `Example` schema name.

```yaml
$:
  config:
    namespace: App\Example\Readme
    outputPath: tests/

Example:
  Foo:
    property: [
      {name: title}
      {name: bar, type: popo, default: Bar::class}
    ]

  Bar:
    property: [
      {name: title}
    ]
```

#### Instantiate data structure from an array.

```php
use App\Example\Readme\Foo;

$data = [
    'title' => 'A title',
    'bar' => [
        'title' => 'Bar lorem ipsum',
    ],
];

$foo = (new Foo)->fromArray($data);

echo $foo->getTitle();
echo $foo->requireBar()->getTitle();
```

Output:

```
A title
Bar lorem ipsum
```

#### Display hierarchy of objects as an array.

```php
use App\Example\Readme\Foo;

$foo = (new Foo);
$foo->requireBar()->setTitle('new value');

print_r($foo->toArray());
```

Output:

```
[
    'title' => null,
    'bar' => [
        'title' => 'new value',
    ],
];
```

_Run `bin/popo generate -s tests/fixtures/popo-readme.yml` or `docker-popo generate -s tests/fixtures/popo-readme.yml` to generate files from this example._

### getter vs requester

Getter methods wil just return a value, while requester methods will throw `UnexpectedValueException` when the requested value has not been set, or it's null. 

_Note_: For `popo` type properties, the requester automatically creates instance of `popo` objects.


## Installation

```sh
composer require popo/generator --dev
```

Note: The installation can be skipped when using docker, see _Docker support_ section.

## Usage

You can either use it as composer dependency or as docker command.

### Generate command

1. Define schema file, see [tests/fixtures](tests/fixtures/) for examples.

2. Generate POPO files, run:
   - with composer
   
      ```sh
      vendor/bin/popo generate -s <schema-path> -o <output-path>
      ```
   - with docker
      ```sh
      docker-popo generate -s <schema-path> -o <output-path>     
     ```
   

_For example: `bin/popo generate -s tests/fixtures/popo.yml` or `docker-popo generate -s tests/fixtures/popo.yml`._

### Command line options

```
Usage:
  generate [options]

Options:
  -s, --schemaPath=SCHEMAPATH                                           Path to schema file or directory
  -c, --schemaConfigFilename[=SCHEMACONFIGFILENAME]                     Path to shared schema configuration
  -o, --outputPath[=OUTPUTPATH]                                         Output path where the files will be generated. Overrides schema settings when set.
  -p, --schemaPathFilter[=SCHEMAPATHFILTER]                             Path filter to match POPO schema files.
  -m, --schemaFilenameMask[=SCHEMAFILENAMEMASK]                         Schema filename mask. [default: "*.popo.yml"]
  -ns, --namespace[=NAMESPACE]                                          Namespace of generated POPO files. Overrides schema settings when set.
  -nr, --namespaceRoot[=NAMESPACEROOT]                                  Remaps namespace and outputPath
  -ig, --ignoreNonExistingSchemaFolder[=IGNORENONEXISTINGSCHEMAFOLDER]  When set, an exception will not be thrown in case missing schemaPath folder [default: false]
  -clp, --classPluginCollection[=CLASSPLUGINCOLLECTION]                 Collection of class names for plugins implementing \Popo\Plugin\ClassPluginInterface
  -mpp, --mappingPolicyPluginCollection[=MAPPINGPOLICYPLUGINCOLLECTION] Collection of class names for plugins implementing \Popo\Plugin\MappingPolicyPluginInterface
  -nsp, --namespacePluginCollection[=NAMESPACEPLUGINCOLLECTION]         Collection of class names for plugins implementing \Popo\Plugin\NamespacePluginInterface
  -pfp, --phpFilePluginCollection[=PHPFILEPLUGINCOLLECTION]             Collection of class names for plugins implementing \Popo\Plugin\PhpFilePluginInterface
  -ppp, --propertyPluginCollection[=PROPERTYPLUGINCOLLECTION]           Collection of class names for plugins implementing \Popo\Plugin\PropertyPluginInterface
```


#### `--schemaPath`

This parameter can either be a path to YAML file, or to a directory, under where YAML configuration files are stored.

This parameter is required.

_Note:_ To provide multiple values use comma as a separator, eg. `-s tests/bundles/,tests/projects/`.

#### `--schemaConfigFilename`

This parameter is optional, but when set a shared schema configuration will be used for all POPO schemas.

#### `--outputPath`

Output path where the files will be generated, the namespace folders will be created automatically.

This parameter is optional, but when set it overrides `outputPath` configured in a schema.

#### `--namespace`

Namespace of generated POPO files.

This parameter is optional, but when set it overrides `namespace` configured in a schema.

#### `--namespaceRoot`

This parameter is optional, but when set it allow set mapping between `namespace` and `outputPath`.

For example, the configuration below would remove `ExampleBundle` from the file path, when generating files
under `outputPath` directory.

```yaml
 namespace: ExampleBundle\AppWithNamespaceRoot\Example
 namespaceRoot: ExampleBundle
```

#### `--schemaPathFilter`

Additional path filter when `schema-path` is set to a folder.

This parameter is optional.

Each schema folder can contain multiple schema files, for example:

```
<bundles>
    |
    |-- <example-foo>
    |         |_ foo.popo.yml
    |         
    |-- <example-bar>
    |         |- bar.popo.yml   
    |         |_ buzz.popo.yml   
    |
    |-- global.config.yml
```

_Run `bin/popo generate -s tests/fixtures/ -p bundles -c tests/fixtures/bundles/project.config.yml` or `docker-popo generate -s tests/fixtures/ -p bundles -c tests/fixtures/bundles/project.config.yml` to generate files from this example._


#### `--schemaFilenameMask`

Filename mask used to locate schema files when using `schema-path-filter`.

Default is `*.popo.yml`.


#### `--ignoreNonExistingSchemaFolder`

Set to true, to ignore errors related to missing schema directories when passing multiple paths with `<schema-path>`, separated by a comma. 

Default is `false`.


#### `--classPluginCollection`

Collection of class names for plugins implementing `\Popo\Plugin\ClassPluginInterface`. 

Default is `[]`.


#### `--mappingPolicyPluginCollection`

Collection of class names for plugins implementing `\Popo\Plugin\MappingPolicyPluginInterface`. 

Default is `[]`.


#### `--namespacePluginCollection`

Collection of class names for plugins implementing `\Popo\Plugin\NamespacePluginInterface`. 

Default is `[]`.


#### `--phpFilePluginCollection`

Collection of class names for plugins implementing `\Popo\Plugin\PhpFilePluginInterface`. 

Default is `[]`.


#### `--propertyPluginCollection`

Collection of class names for plugins implementing `\Popo\Plugin\PropertyPluginInterface`. 

Default is `[]`.


### Report Command

The `report` command shows list of defined / inherited properties.

```sh
vendor/bin/popo report -s <schema-path> \
  -c [schema-config-filename] \
  -p [schema-path-filter]
```

For example, report for `tests/fixtures/popo-readme.yml` file. 

```
bar
 popo-config Example::Foo - tests/fixtures/popo-readme.yml
title
 popo-config Example::Bar - tests/fixtures/popo-readme.yml
 popo-config Example::Foo - tests/fixtures/popo-readme.yml
```

_Run `bin/popo report -s tests/fixtures/popo-readme.yml` or `docker-popo report -s tests/fixtures/popo-readme.yml` to generate files from this example._


## POPO Schema

```yaml
$: # file-config, shared configuration for all POPO objects in current schema file
  config:
    namespace: string
    outputPath: string
    namespaceRoot: string|null # if set remaps namespace and outputPath
    extend: string|null # which class POPO objects should extend from
    implement: string|null # which interface POPO objects should implement
    comment: string|null # Class docblock comment
    phpComment: string|null # Generated PHP File docblock comment
  default: array # default values
  property: array #shared properties

SchemaName: # schema-config
  $: # shared configuration for all POPO objects in SchemaName, in all schema files
    config:
      namespace: string
      outputPath: string
      namespaceRoot: string|null
      extend: string|null
      implement: string|null
      comment: string|null
      phpComment: string|null
    default: array
    property: [{
      name: string,
      type:
        type: string
        default: string
        supportedTypes: ['array','bool','float','int','string','mixed','const','popo', 'datetime'],
      comment: string|null, # Property docblock comment
      default: mixed, # default value
      itemType: string|null, # collection item type
      itemName: string|null, # collection item singular name
      extra: {timezone: ..., format: ...}, #for datetime property
      mappingPolicy: ['none', 'lower', 'upper', 'camel-to-snake', 'snake-to-camel']
    }]

  PopoName: # popo-config
    config:
      namespace: string
      outputPath: string
      namespaceRoot: string|null
      extend: string|null
      implement: string|null
      comment: string|null
      phpComment: string|null
    default: array
    property: [{
      name: string,
      type:
        type: string
        default: string
        supportedTypes: ['array','bool','float','int','string','mixed','const','popo', 'datetime'],
      comment: string|null,
      default: mixed,
      itemType: string|null,
      itemName: string|null,
      extra: {timezone: ..., format: ...}, #for datetime property
      mappingPolicy: ['none', 'lower', 'upper', 'camel-to-snake', 'snake-to-camel']
    }]
```

POPO Schema can be defined and extended on few levels, and it can be defined in multiple files.

The `popo-config` values override `schema-file-config` values, and `schema-file-config` values overwrite `schema-config` values.

On top of that, there is a `global-config` that is defined when using `--schemaConfigFilename` parameter.

<img src="doc/popo_schema.png" width="400" alt="POPO Schema" />

#### `schema-config`

The configuration was defined as a `Schema` property.
It will be used by **_all_** POPO objects in **_all_** files, under given schema.


#### `schema-file-config` 

The configuration was defined as a `SchemaFile` property.
It will be used by **_all_** POPO objects in **_current_** file.


#### `popo-config`

The configuration was defined as a `POPO` property.
It will be used by one **_specific_** POPO objects in **_current_** file.


See [tests/fixtures](tests/fixtures/) for schema examples.

## Property type list

- `array`
- `bool`
- `float`
- `int`
- `string`
- `mixed`
- `const`
- `popo`
- `datetime`

## Collection support

Use property's `itemType` and `itemName` to create properties with collection item type support. For example
using `Buzz::class` as itemType and `buzz` for the itemName, would generate: `addBuzz(Buzz $item)`.

## Data Mapping

`mappingPolicy` and `mappingPolicyValue` options can be used in case where schema keys have to be remapped. 

For example, consider the following schema, where each of the properties have specific mapping policy types:

- `BLOG_TITLE`: snake case + upper case
- `blog_data`: snake case
- `commentThread`: no mapping

```yaml
$:
  config:
    namespace: App\Example\MappingPolicy
    outputPath: tests/

MappingPolicy:
  Blog:
    property:
      -
        name: blogTitle
        mappingPolicy:
          - \Popo\Plugin\MappingPolicy\CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME
          - \Popo\Plugin\MappingPolicy\UpperMappingPolicyPlugin::MAPPING_POLICY_NAME

      -
        name: blogData
        type: popo
        default: BlogData::class
        mappingPolicy:
          - \Popo\Plugin\MappingPolicy\CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME

      -
        name: commentThread
        default: ['one','two','three']
        type: array

  BlogData:
    property:
      -
        name: someValue

  DocumentData:
    property:
      -
        name: someTitle
        default: Lorem Ipsum
        mappingPolicyValue: some_title

      -
        name: someValue
        default: 123
        type: int
        mappingPolicyValue: SOME_VALUE
```

#### Static mapping

The `mappingPolicyValue` can be used to map specific schema key to different value.
See `DocumentData` above in schema example.

#### Dynamic mapping

Dynamic mapping might be useful, for example, in cases where schema files are generated automatically,
and the schema keys have to be remapped.
See `Blog` above in schema example.

_Note:_ Regardless of mapping type, there is no runtime performance difference when using generated POPO classes.

```php
print_r((new Blog())->toArray());
```

```
[
  'BLOG_TITLE' => null,
  'blog_data' => [
      'someValue' => null,
  ],
  'commentThread' => ['one','two','three'],
]
```


### Extra mapping methods

There are various methods to help execute runtime mapping by POPO classes.

_Note:_ New mapping policies can be provided by additional plugins.

Default array mapping methods:

- `fromMappedArray`
- `toMappedArray`
- `toArrayCamelToSnake`
- `toArrayLower`
- `toArraySnakeToCamel`
- `toArrayUpper`


For example:

```php
$documentData = (new DocumentData())
    ->setSomeTitle('a title')
    ->setSomeValue(111);
```    

```php
print_r($documentData->toArray());
```
```
[
  'some_title' => 'a title',
  'SOME_VALUE' => 111,
]
```

```php
print_r($documentData->toArraySnakeToCamel());
```

```
[
    'someTitle' => 'a title',
    'someValue' => 111,
]
```

### toMappedArray(...) / fromMappedArray(...)

_Note:_ You can skip this step, if you install POPO library as part of production code dependencies.

When using `fromMappedArray(...)`and `toMappedArray(...)` , it's recommended,
to redefine the constant values used for mapping types on the project level and use these values.

For example:

```php
namespace MyProject

class MyConfig
{
  public const POPO_MAPPING_POLICY_CAMEL_TO_SNAKE = 'camel-to-snake';
  public const POPO_MAPPING_POLICY_LOWER = 'lower';
  public const POPO_MAPPING_POLICY_NONE = 'none';
  public const POPO_MAPPING_POLICY_SNAKE_TO_CAMEL = 'snake-to-camel';
  public const POPO_MAPPING_POLICY_UPPER = 'upper';
}
```

```php
$documentData = (new DocumentData())
    ->setSomeTitle('a title')
    ->setSomeValue(111);
    
print_r($documentData->toMappedArray(MyConfig::POPO_MAPPING_POLICY_UPPER));
```

```
[
  'SOME_TITLE' => 'a title'
  'SOME_VALUE' => 111
]
```

For more info, see plugins implementing `Popo\Plugin\MappingPolicy\MappingPolicyPluginInterface`.

## Additional functionality with plugins

Apart from the typical setters and getters POPO objects have additional helper methods which ease access to, and offer
more insight about the data that they represent.

Some of the methods supported by `class` plugins:

- `isNew`
- `fromArray`
- `fromMappedArray`
- `toArray`
- `toMappedArray`
- `toArrayCamelToSnake`
- `toArraySnakeToCamel`
- `modifiedToArray`
- `requireAll`
- `listModifiedProperties`
- ...

Some of the methods supported by `property` plugins:

- `set`
- `get`
- `require`
- `has`
- `addCollectionItem`
- ...

_Note:_ Plugins can be disabled with:

```php
$configurator = (new \Popo\PopoConfigurator)
    ->setClassPluginCollection([])
    ->setPropertyPluginCollection([]);
```

## Generating Code with Plugins

POPO generation process is split into few parts:

- PHP file header code generation
- Namespace code generation
- Class methods code generation
- Properties and property methods code generation

Each part has corresponding set of plugins.

- #### Popo\Plugin\PhpFilePluginInterface
  Generates code related to PHP header, e.g. strict type, file comments.
  ```php
    interface PhpFilePluginInterface
    {
        public function run(PhpFile $file, Schema $schema): PhpFile;
    }
    ```

- #### Popo\Plugin\NamespacePluginInterface
  Generates code related to namespace, e.g. aliases, use statements.

    ```php
    interface NamespacePluginInterface
    {
        public function run(PhpNamespace $namespace): PhpNamespace;
    }
    ```

- #### Popo\Plugin\ClassPluginInterface
  Generates code related to class methods, e.g. `toArray()`, `isNew()`, `extends` or `implements` keywords.

    ```php
    interface ClassPluginInterface
    {
        public function run(BuilderPluginInterface $builder): void;
    }
    ```

- #### Popo\Plugin\PropertyPluginInterface
  Generates code related to properties and their methods, e.g. `hasFoo()`, `getFoo()`, `requireFoo()`.
  
    ```php
    interface PropertyPluginInterface
    {
        public function run(BuilderPluginInterface $builder, Property $property): void;
    }
    ```
  
### Mapping policy plugins

The plugins responsible for code related to schema key mapping, e.g. transforming `foo_id` to `fooId`.

```php
interface Popo\Plugin\MappingPolicyPluginInterface
{
    public function run(string $key): string;
}
```

#### Example of plugin setup:

```php
$configurator = (new \Popo\PopoConfigurator)
    ->addPhpFilePluginClass(PhpFilePluginClass::class)
    ->addNamespacePluginClass(NamespacePluginClass::class)
    ->addClassPluginClass(PluginClass1:class)
    ->addClassPluginClass(PluginClass2:class)
    ->addPropertyPluginClass(PluginProperty1::class)
    ->addPropertyPluginClass(PluginProperty2::class)
    ...
```


See [src/Popo/Plugin](src/Popo/Plugin).

## More Examples

See [fixtures](tests/fixtures/popo.yml) and [tests](tests/suite/App/PopoTest.php) for more usage examples.

## PHP version compatibility

- POPO `v1.x` - PHP 7.2+
- POPO `v2.x` - PHP 7.2+
- POPO `v3.x` - PHP 7.4+
- POPO `v4.x` - PHP 7.4+
- POPO `v5.x` - PHP 7.4+, PHP 8
- POPO `v6.x` - PHP 8

## Composer script

Add popo scrip to composer and run `composer popo` in a project.

```
    "scripts": {
        "popo": [
            "bin/popo generate -s <schema-path>"
        ]
    },
    "scripts-descriptions": {
        "popo": "Generate POPO files"
    }
```

## Docker support

With docker you can generate files without installing `POPO` as dependency in the project.

```
docker container run -it --rm oliwierptak/popo /app/bin/popo
```

You can either run the command directly, or create an alias, e.g.:

```
alias docker-popo='docker container run -it --rm oliwierptak/popo /app/bin/popo ${@}'
```

For example:

```
docker-popo generate -s tests/fixtures/popo.yml
docker-popo report -s tests/fixtures/popo.yml
``` 

See also: [bin/docker-popo](bin/docker-popo).

## Fun fact!

[PopoConfigurator](src/Popo/PopoConfigurator.php) class which is required by POPO library to run,
was generated by POPO library, and it even has its own [popo schema](popo.yml).

