# POPO

POPO - "Plain Old Php Object" was inspired by "Plain Old Java Object" (POJO) concept.

POPO can also locate, load, validate, and combine YAML schema to generate PHP source code files.
The schema supports inheritance, collections and encapsulation of other POPO objects.


### POPO schema example

A POPO schema is a YAML file describing POPO objects and their relations.

```yaml
$:
  namespace: App\Example\Readme
  outputPath: tests/

Example:
  Foo:
    default:
      bar: Bar::class
    property: [
      {name: title},
      {name: bar, type: popo},
    ]}}

  Bar:
    property: [
      {name: title}
    ]}}

```

### Example usage of generated files

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

```
A title
Bar lorem ipsum
```


```php
use App\Example\Readme\Foo;

$foo = (new Foo);
$foo->requireBar()->setTitle('new value');

print_r($foo->toArray());
```

```
[
    'title' => null,
    'bar' => [
        'title' => 'new value',
    ],
];
```


## Installation

```sh
composer require popo/generator --dev
```


## Usage

1. Define schema, see [tests/fixtures](tests/fixtures/) for examples.

2. Generate POPO files, run:

    ```sh
    vendor/bin/popo generate -o <output-path> -s <schema-path>
    ```

_Note_: For example: `bin/popo generate -o tests/ -s tests/fixtures/`


#### <output-path>

Root path where the files will be generated, the namespace folders will be created automatically.

#### <schema-path>

The `<schema-path>` parameter can either be a path to YAML file, or to a directory, under where YAML configuration files are stored.

### Schema Path Directory Structure

Schema directory structure, each schema folder can contain multiple schema files:

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
...
```

### Schema definition example

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

Run `bin/popo generate -o tests/ -s tests/fixtures/popo.yml` to generate files from this schema.

_Note_: See [tests/fixtures](tests/fixtures) for more examples.

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
