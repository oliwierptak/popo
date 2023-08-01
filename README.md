# POPO

[![Build and run tests](https://github.com/oliwierptak/popo/actions/workflows/main.yml/badge.svg?branch=main)](https://github.com/oliwierptak/popo/actions/workflows/main.yml)

**POPO** - "Plain Old Php Object" was inspired by "Plain Old Java Object" (POJO) concept.

POPO generator can also locate, load, validate, and combine schemas to create PHP source code files, representing
Arrays / Data Structures / Data Transfer Objects / Doctrine ORM Entities / MongoDB ODM Documents.

POPO Schema can be defined and extended on few levels, and it can be defined in multiple files.

### Examples

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


## Installation

```sh
composer require popo/generator --dev
```

Note: The installation can be skipped when using docker, see _Docker support_ section.

## Usage

You can either use it as composer dependency or as docker command.

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

## POPO Schema

POPO Schema can be defined and extended on few levels- and it can be defined in multiple files.

The schema supports key mapping- inheritance- collections and encapsulation of other POPO objects.

### Schema Definition

```yaml
$: # file-config, shared configuration for all POPO objects in current schema file
  config:
    namespace: string
    outputPath: string
    namespaceRoot: string|null
    extend: string|null
    implement: string|null
    comment: string|null
    phpComment: string|null
    use: array<string>|[]
    attribute: string|null
    attributes: array<key, value>
  default: array
  property: array

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
      use: array<string>|[]
      attribute: string|null,
      attributes: array<key, value>
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
      extra: {timezone: ..., format: ...},
      attribute: string|null,
      attributes: array<key, value>
      mappingPolicy: ['none', 'lower', 'upper', 'camel-to-snake', 'snake-to-camel'],
      mappingPolicyValue: string|null
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
      use: array<string>|[]
      attribute: string|null,
      attributes: array<key, value>
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
      extra: {timezone: ..., format: ...},
      attribute: string|null,
      attributes: array<key, value>
      mappingPolicy: ['none', 'lower', 'upper', 'camel-to-snake', 'snake-to-camel'],
      mappingPolicyValue: string|null
    }]
```


### Schema configuration options

### `namespace`

Defines generated class namespace.

```yaml
config:
  namespace: App\Example
  ...
```

### `outputPath`

Defines output directory.

```yaml
config:
  outputPath: src/
  ...
```

### `namespaceRoot`

Defines the begging of `outputPath` that should be removed.
For example to generated files under `src/Example` with `App\Example` namespace.

```yaml
config:
  namespace: App\Example
  outputPath: src/
  namespaceRoot: App\
  ...
```

### `extend`

Which class should the generated class extend from. Must start with **\\** or contain `::class`.

```yaml
config:
  extend: \App\Example\AbstractDto::class
  ...
```

### `implement`

Which interface should the generated class implement. Must start with **\\** or contain `::class`.

```yaml
config:
  implement: \App\Example\DtoInterface::class
   ...
```


### `comment`

Class comment.

```yaml
config:
  comment: |
    @Document(collection="events")
...
```

### `phpComment`

Generated PHP file comment.

```yaml
config:
  phpComment: |
    Auto generated.
    @SuppressWarnings(PHPMD)
    @phpcs:ignoreFile
...
```

### `use`

Import statements.

```yaml
  config:
    use:
      - Doctrine\ODM\MongoDB\Mapping\Annotations\Document
      - Doctrine\ODM\MongoDB\Mapping\Annotations\Field
      - Doctrine\ODM\MongoDB\Mapping\Annotations\Id
    ...
```

### `attribute`

Class attributes value.

```yaml
  config:
    attribute: |
      #[Doctrine\ORM\Mapping\Entity(repositoryClass: LogEventRepository::class)]
    ...
```


### `attributes`: `array`

Attribute value as collection. Supported values:

- `name`
- `value`: `mixed`


```yaml
  config:
    attributes:
      - name: Doctrine\ORM\Mapping\Entity
        value: {repositoryClass: LogEventRepository::class}
    ...
```


### Property configuration options

### `name`
    
The name of the property. The property related methods will be generated based on this value. For example `getFooBar()`.
This is required parameter.

```yaml
property: 
  - name: title
    ...
```


### `type`

Property data type, supported are:

- `array`
- `bool`
- `float`
- `int`
- `string`
- `mixed`
- `const`
- `popo`
- `datetime`
  
Default property type is `string`.

```yaml
property: 
  - name: precision
    type: float
    ...
```

### `comment`

Docblock value for property and methods.

```yaml
property: 
  - name: title
    comment: Lorem ipsum
    ...
```

### `default: mixed`

Default value.

```yaml
property: 
  - name: items
    default: \App\ExampleInterface::TEST_BUZZ
    ...
```

### `extra: array`

Used by `datetime` data type. Supported values:

  - `format`
  - `timezone`

```yaml
property: 
    - name: created
      type: datetime
      extra: 
          timezone: Europe/Paris
          format: D, d M y H:i:s O
    ...
```

### `itemType`

Used by `array` data type together with `itemName` element. Describes type of single array element.

```yaml
property:
    - name: products
      type: array
      itemType: Product::class
    ...
```

### `itemName`

Used by `array` data type. Describes name of single array element. For example: `setProducts(array $products)`, `addProduct(Product $item)`.

```yaml
property:
    - name: products
      type: array
      itemName: product
    ...
```

### `attribute`

Attribute value.

```yaml
property:
    - name: price
      attribute: |
      #[Doctrine\ORM\Mapping\Column(type: Types::INTEGER)]
    ...
```


### `attributes`: `array`

Attribute value as collection. Supported values:

- `name`
- `value`: `mixed`


```yaml
property:
    - name: id
      attributes:
        - name: Doctrine\ORM\Mapping\Column
          value: ['length: 255']
    ...
```


### `mappingPolicy: array`

Dynamically remaps property names, for example, `fooId` => `FOO_ID`. Supported values:

- `none`
- `lower`
- `upper`
- `camel-to-snake`
- `snake-to-camel`

```yaml
property:
  - name: fooId
    mappingPolicy:
      - camel-to-snake
      - upper
    ...
```

### `mappingPolicyValue`

Statically remaps property names, for example, `fooId` => `FOO_ID`.

```yaml
property:
  - name: fooId
    mappingPolicyValue: FOO_ID
  ...
```



### Schema Inheritance

The `popo-config` values override `schema-file-config` values- and `schema-file-config` values overwrite `schema-config` values.

On top of that there is a `global-config` that is defined when using `--schemaConfigFilename` parameter.

<img src="doc/popo_schema.png" width="400" alt="POPO Schema" />

#### `schema-config`

The configuration was defined as a `Schema` property.
It will be used by **_all_** POPO objects in **_all_** files under given schema.


#### `schema-file-config`

The configuration was defined as a `SchemaFile` property.
It will be used by **_all_** POPO objects in **_current_** file.


#### `popo-config`

The configuration was defined as a `POPO` property.
It will be used by one **_specific_** POPO objects in **_current_** file.


See [tests/fixtures](tests/fixtures/) for schema examples.


## Property name remapping

POPO can remap property keys names, for example change `foo_id` into `fooId`.

See [Property Name Remapping](README_PROPERTY_NAME_REMAPPING.md) doc.


## Pluggable architecture

New functionality can be provided on the command line, or via configuration.

See [Plugins](README_PLUGINS.md) doc.


## Doctrine Support

See [Doctrine Support](README_DOCTRINE.md) doc.


## Command line options

See [Command Line Options](README_COMMAND_LINE.md) doc.



## More Examples

See [fixtures](tests/fixtures/popo.yml) and [tests](tests/suite/App/PopoTest.php) for more usage examples.

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


## PHP version compatibility

- POPO `v1.x` - PHP 7.2+
- POPO `v2.x` - PHP 7.2+
- POPO `v3.x` - PHP 7.4+
- POPO `v4.x` - PHP 7.4+
- POPO `v5.x` - PHP 7.4+
- POPO `v6.x` - PHP 8+


## POPO schema example

Schema example that produces generated [PopoConfigurator](src/Popo/PopoConfigurator.php) class. 

```yaml
$:
  config:
    namespace: Popo
    outputPath: src/
    phpComment: |
      @SuppressWarnings(PHPMD)
      @phpcs:ignoreFile

Popo:
  PopoConfigurator:
    default:
      phpFilePluginCollection:
        - \Popo\Plugin\PhpFilePlugin\StrictTypesPhpFilePlugin::class
        - \Popo\Plugin\PhpFilePlugin\CommentPhpFilePlugin::class
      namespacePluginCollection:
        - \Popo\Plugin\NamespacePlugin\UseStatementPlugin::class
      classPluginCollection:
        - \Popo\Plugin\ClassPlugin\ClassAttributePlugin::class
        - \Popo\Plugin\ClassPlugin\ClassCommentPlugin::class
        - \Popo\Plugin\ClassPlugin\ConstPropertyClassPlugin::class
        - \Popo\Plugin\ClassPlugin\DateTimeMethodClassPlugin::class
        - \Popo\Plugin\ClassPlugin\ExtendClassPlugin::class
        - \Popo\Plugin\ClassPlugin\ImplementClassPlugin::class
        - \Popo\Plugin\ClassPlugin\IsNewClassPlugin::class
        - \Popo\Plugin\ClassPlugin\ListModifiedPropertiesClassPlugin::class
        - \Popo\Plugin\ClassPlugin\MetadataClassPlugin::class
        - \Popo\Plugin\ClassPlugin\ModifiedToArrayClassPlugin::class
        - \Popo\Plugin\ClassPlugin\PopoMethodClassPlugin::class
        - \Popo\Plugin\ClassPlugin\RequireAllClassPlugin::class
        - \Popo\Plugin\ClassPlugin\UpdateMapClassPlugin::class
        - \Popo\Plugin\ClassPlugin\FromArrayClassPlugin::class
        - \Popo\Plugin\ClassPlugin\FromMappedArrayClassPlugin::class
        - \Popo\Plugin\ClassPlugin\ToArrayClassPlugin::class
        - \Popo\Plugin\ClassPlugin\ToMappedArrayClassPlugin::class
        - \Popo\Plugin\ClassPlugin\MappingPolicyMethod\ToArrayLowercasePlugin::class
        - \Popo\Plugin\ClassPlugin\MappingPolicyMethod\ToArrayUppercasePlugin::class
        - \Popo\Plugin\ClassPlugin\MappingPolicyMethod\ToArraySnakeToCamelPlugin::class
        - \Popo\Plugin\ClassPlugin\MappingPolicyMethod\ToArrayCamelToSnakePlugin::class
      propertyPluginCollection:
        - \Popo\Plugin\PropertyPlugin\AddItemPropertyMethodPlugin::class
        - \Popo\Plugin\PropertyPlugin\DefinePropertyPlugin::class
        - \Popo\Plugin\PropertyPlugin\GetPropertyMethodPlugin::class
        - \Popo\Plugin\PropertyPlugin\HasPropertyMethodPlugin::class
        - \Popo\Plugin\PropertyPlugin\RequirePropertyMethodPlugin::class
        - \Popo\Plugin\PropertyPlugin\SetPropertyMethodPlugin::class
      mappingPolicyPluginCollection:
        none: \Popo\Plugin\MappingPolicy\NoneMappingPolicyPlugin::class
        lower: \Popo\Plugin\MappingPolicy\LowerMappingPolicyPlugin::class
        upper: \Popo\Plugin\MappingPolicy\UpperMappingPolicyPlugin::class
        snake-to-camel: \Popo\Plugin\MappingPolicy\SnakeToCamelMappingPolicyPlugin::class
        camel-to-snake: \Popo\Plugin\MappingPolicy\CamelToSnakeMappingPolicyPlugin::class
    property: [
      {name: schemaPath}
      {name: namespace}
      {name: namespaceRoot}
      {name: outputPath}
      {name: phpFilePluginCollection, type: array, itemType: string, itemName: phpFilePluginClass}
      {name: namespacePluginCollection, type: array, itemType: string, itemName: namespacePluginClass}
      {name: classPluginCollection, type: array, itemType: string, itemName: classPluginClass}
      {name: propertyPluginCollection, type: array, itemType: string, itemName: propertyPluginClass}
      {name: mappingPolicyPluginCollection, type: array, itemType: string, itemName: mappingPolicyPluginClass}
      {name: schemaConfigFilename}
      {name: schemaPathFilter}
      {name: schemaFilenameMask, default: '*.popo.yml'}
      {name: shouldIgnoreNonExistingSchemaFolder, type: bool}
    ]}}
```

[link-packagist]: https://packagist.org/packages/popo/generator
[link-travis]: https://travis-ci.org/popo/generator
[link-author]: https://github.com/oliwierptak/popo