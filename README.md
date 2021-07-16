# POPO

POPO - "Plain Old Php Object" was inspired by "Plain Old Java Object" (POJO) concept.

POPO generator can also locate, load, validate, and combine POPO schemas to create PHP source code files,
representing Data Structures / Data Transfer Objects.

The schema supports inheritance, collections and encapsulation of other POPO objects.


### Example

An example schema in YAML format, describing properties and relations of POPO objects.
In this case, `Foo` defines `Bar` as its property.


```yaml
$:
  namespace: App\Example\Readme
  outputPath: tests/

Example:
  Foo:
    property: [
      {name: title},
      {name: bar, type: popo, default: Bar::class},
    ]}}

  Bar:
    property: [
      {name: title}
    ]}}
```


#### Instantiate hierarchy of objects from an array.

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
echo $foo->getBar()->getTitle();
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

_Run `bin/popo generate -o tests/ -s tests/fixtures/popo-readme.yml` to generate files from this example._

### getter vs requester 

The method `requireBar()` automatically creates instance of Bar in case where the value has not been set yet,
while the method `getBar()` simply returns Bar's property value.


## Installation

```sh
composer require popo/generator --dev
```


## Usage

1. Define schema, see [tests/fixtures](tests/fixtures/) for examples.

2. Generate POPO files, run:

    ```sh
    vendor/bin/popo generate -s <schema-path> [-o <output-path>] [-m <namespace>] [-p <schema-path-filter>]
    ```

_Note: For example: `bin/popo generate -s tests/fixtures`_

### <schema-path>

This parameter can either be a path to YAML file, or to a directory, under where YAML configuration files are stored.

This parameter is required.

### [output-path]

Output path where the files will be generated, the namespace folders will be created automatically.

This parameter is optional, but when set it overrides `outputPath` configured in a schema.

### [namespace]

Namespace of generated POPO files.

This parameter is optional, but when set it overrides `namespace` configured in a schema.


### [schema-path-filter]

Additional path filter when `schema-path` is set to a folder. Default value is `bundles`.

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
```

_This parameter is optional._

## Popo Schema

```yaml
$: #shared configuration
   namespace: string
   outputPath: string
   extend: string|null
   implement: string|null
   comment: string|null
   default: array

SchemaName:
   PopoName:
     config: array #overrides values from shared configuration, except for 'default'
     default: array #overrides values from shared configuration's 'default'
     property: [{ #property list
        #property definition
        name: string,
        type: {
           type: string|null,
           default: string,
           supportedTypes: ['array','bool','float','int','string','mixed','const','popo']
        },
        comment: string|null,
        default: mixed|null,
        itemType: string|null,
        itemName: string|null
      }]
```

### Property type list

- array
- bool
- float
- int
- string
- mixed
- const
- popo


### Additional methods

Apart from the typical setters and getters POPO objects have additional helper methods 
which ease access to, and offer more insight about the data that they represent.

The following methods are supported:

- isNew
- fromArray
- toArray
- requireAll
  
Property specific methods
- set
- get
- require
- has
- addCollectionItem


### Collection support

Use property's `itemType` and `itemName` to create properties that support array item types.


### More Examples

See [fixtures](tests/fixtures/popo.yml) and [tests](tests/suite/Unit/PopoTest.php) for more usage examples.

#### popo.yml

```yaml
$:
  namespace: App\Example\Popo
  outputPath: tests/
  extend: App\AbstractExample::class
  implement: App\ExampleInterface::class
  comment: Popo Example. Auto-generated.
  default:
    title: Hakuna Matata

Example:
  Foo:
    config:
      comment: Foo example lorem ipsum
    default: 
      bar: Bar::class
    property: [
      {name: fooId, type: int, comment: Foo ID COMMENT},
      {name: title},
      {name: value, type: int, default: \App\ExampleInterface::TEST_BUZZ},
      {name: bar, type: popo},
    ]}}

  Bar:
    default:
      title: Lorem Ipsum
      buzz: \App\Example\Popo\Fizz\Buzz::class
      itemType: \App\Example\Popo\Fizz\Buzz::class
    property: [
      {name: title}
      {name: buzz, type: popo}
      {name: buzzCollection, type: array, itemName: buzz}
    ]}}

  Buzz:
    config:
      namespace: App\Example\Popo\Fizz
    property: [
      {name: value, default: Buzzzzz}
    ]}}
```

_Run `bin/popo generate -o tests/ -s tests/fixtures/popo.yml` to generate files from this schema._



### PHP version compatibility

- POPO `v1.x` - PHP 7.2+
- POPO `v2.x` - PHP 7.2+
- POPO `v3.x` - PHP 7.4+
- POPO `v4.x` - PHP 8+
- POPO `v5.x` - PHP 8+


### Composer script

Add popo scrip to composer and run `composer popo` in a project.

```
    "scripts": {
        "popo": [
            "vendor/bin/popo generate -o <output-path> -s <schema-path>"
        ]
    },
    "scripts-descriptions": {
        "popo": "Generate POPO files"
    }
```
