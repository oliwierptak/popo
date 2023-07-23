# POPO

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


## Doctrine Support


### Doctrine ORM

Example of Doctrine ORM Entity mapping:

```yaml
Entity:
  $:
    config:
      namespace: App\Example\Entity
      use:
        - App\Repository\LogEventRepository
        - Doctrine\DBAL\Types\Types
  LogEvent:
    config:
      attribute: |
        #[Doctrine\ORM\Mapping\Entity(repositoryClass: LogEventRepository::class)]
    property:
      - name: id
        attributes:
          - name: Doctrine\ORM\Mapping\Id
          - name: Doctrine\ORM\Mapping\GeneratedValue
          - name: Doctrine\ORM\Mapping\ORM\Column

      - name: service
        attributes:
          - name: Doctrine\ORM\Mapping\Column
            value: ['length: 255']

      - name: statusCode
        type: int
        attribute: |
          #[Doctrine\ORM\Mapping\Column(type: Types::INTEGER)]

      - name: logDate
        type: datetime
        attribute: |
          #[Doctrine\ORM\Mapping\Column(type: Types::DATETIME)]
        extra:
          - timezone: Europe/Berlin
            format: Y-m-d\TH:i:sP
```

### MongoDB ODM

Example of MongoDB ODM Document mapping:

```yaml
Document:
  LogEvent:
    config:
      comment: |
        @Document(collection="events")
    property:
      - name: id
        comment: '@Id'

      - name: service
        comment: '@Field(type="string")'

      - name: statusCode
        type: int
        comment: '@Field(type="int")'

      - name: logDate
        type: datetime
        comment: '@Field(type="date")'
        extra:
          - timezone: Europe/Berlin
            format: Y-m-d\TH:i:sP
```


Usage with Doctrine:

```php
use App\Example\Entity\LogEvent;

$document = new LogEvent();
$document->service('service-name');
$document->setStatusCode(201);
$document->setLogDate(new DateTime());

$em->persist($document);
$em->flush();
```

See [popo-doctrine-odm.yml](tests%2Ffixtures%2Fpopo-doctrine-odm.yml)  for full schema example.


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

POPO Schema can be defined and extended on few levels, and it can be defined in multiple files.

The schema supports key mapping, inheritance, collections and encapsulation of other POPO objects.

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
    use: array<string>|[] # Import block in generated PHP class
    attribute: string|null # Class attributes as string
    attributes: array<key, value> # Class attributes as key value pairs
  default: array # default values
  property: array # shared properties

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
      attribute: string|null, # Class attributes as string
      attributes: array<key, value> # Class attributes as key value pairs
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
      attribute: string|null, # Property attributes as string
      attributes: array<key, value> # Property attributes as key value pairs
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
      attribute: string|null, # Class attributes as string
      attributes: array<key, value> # Class attributes as key value pairs
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
      attribute: string|null, # Property attributes as string
      attributes: array<key, value> # Property attributes as key value pairs
      mappingPolicy: ['none', 'lower', 'upper', 'camel-to-snake', 'snake-to-camel'],
      mappingPolicyValue: string|null
    }]
```

### Schema configuration options

- `namespace`
- `outputPath`
- `namespaceRoot`
- `extend`
- `implement`
- `comment`
- `phpComment` 
- `use`
- `attribute`
- `attributes`

### Property type list

- `array`
- `bool`
- `float`
- `int`
- `string`
- `mixed`
- `const`
- `popo`
- `datetime`



## Schema structure

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

## Property name remapping

POPO can remap property keys names, for example change `foo_id` into `fooId`.

See [Property Name Remapping](README_PROPERTY_NAME_REMAPPING.md) doc.

## Pluggable architecture

New functionality can be provided on the command line, or via configuration.

See [Plugins](README_PLUGINS.md) doc.

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
- POPO `v5.x` - PHP 7.4+, PHP 8
- POPO `v6.x` - PHP 8


## Fun fact!

[PopoConfigurator](src/Popo/PopoConfigurator.php) class which is required by POPO library to run,
was generated by POPO library, and it even has its own [popo schema](popo.yml).

