# POPO

POPO - "Plain Old Php Object" was inspired by "Plain Old Java Object" (POJO) concept.

POPO can also locate, load, validate, and combine JSON schemas to generate PHP source code files.
The schema supports inheritance, collections and encapsulation of other POPO objects.

```php
use App\Popo\Foo;

$data = [        
    'title' => 'A title',
    'bar' => [
        'value' => 'Bar lorem ipsum'
    ]
];

$foo = (new Foo)->fromArray($data);

echo $foo->getTitle();
echo $foo->getBar()->getValue();
```

```
A title
Bar lorem ipsum
```

```php
$foo->getBar()->setValue('new value');
$data = $foo->toArray();

$data = [
    'title' => 'A title',
    'bar' => [
        'value' => 'new value'
    ]
];
```

## Installation

```sh
composer require popo/generator` --dev
```


## Usage

1. Define schema, see [tests/fixtures](tests/fixtures/) for examples.

2. Generate configuration file, run:

    ```sh
    bin/popo configure
    ```

3. Generate POPO files, run:

    ```sh
    vendor/bin/popo generate -c <path-to-config>
    ```

_Note_: The command line arguments allow to globally override every setting. See `--help` for details.


## Schema directory structure
Schema directory structure, each schema folder can contain multiple schema files:

```
<schema directory>
    |
    |-- <bundle_name>
    |       |_ schema
    |         |- <bundle_name>.schema.json   
    |         |- ...   
    |         |- ...   
    |         ...    
    |
    |-- <another_bundle_name>
    |       |_ schema
    |         |- <another_bundle_name>.schema.json  
    |         |- ...
    |         |- ...
    |         ...
...
```

For example:
``` 
popo
    bar
        schema
            bar.schema.json
            buzz.schema.json
            foo.schema.json
    buzz
        schema
            bar.schema.json
            buzz.schema.json

    foo
        schema
            foo.schema.json
```

There can only be one `main schema` per bundle, produced from schema files under schema folder,
because each of the schema definitions will be merged into one POPO object.
 
The additional schema definitions are merged together and added as properties to `main schema`.

## Schema definition

A schema is a JSON file describing a POPO object.

```json
{
  "popoSchema": {
    "name": "<string>",
    "schema": "<propertySchema[]>",
    "abstract": "[<bool>]",
    "extends": "[<string>]",
    "extension": "[<string>]",
    "returnType": "[<string>]",
    "withPopo": "[<bool>]",
    "withInterface": "[<bool>]",
    "namespaceWithInterface": "<string>"
  },
  "propertySchema": {
    "name": "<string>",
    "type": "<array|bool|float|int|string|popo|mixed>",
    "collectionItem": "[<type>]",
    "singular": "[<string>]",
    "docblock": "[<string>]",
    "default": "[<mixed>|\\Php\\Const::VALUE]"
  }
}
```

## POPO objects definition

Schema examples:

`foo/schema/foo.schema.json` schema:

```json
[
  {
    "name": "Foo",
    "schema": [
      {
        "name": "title",
        "type": "string"
      }
    ]
  }
]
```


`bar/schema/bar.schema.json` schema:

```json
[
  {
    "name": "Bar",
    "schema": [
      {
        "name": "value",
        "type": "string",
        "default": "Lorem Ipsum Default Bar Value"
      }
    ]
  }
]
```

Running `generate` will produce two independent POPO objects `Foo` and `Bar`.


## Relations and extending already defined schemas

At this point `Foo` has no idea about `Bar` and vice versa.
However, it could be useful to be able to extend schemas that were already defined. 

To achieve this, you can either `add` or `inject` property.


### Case 1: Single project / library

Main schema: `Foo`.

One schema file, no bundles.

`Foo` adds `bar` as its own property. `Bar` does not need to be modified. 

**`foo/schema/foo.schema.json`** schema:

```json
[
  {
    "name": "Foo",
    "schema": [
      {
        "name": "title",
        "type": "string"
      },
      {
        "name": "bar",
        "type": "Bar",
        "docblock": "Adds Bar property to Foo from Foo bundle"
      }
    ]
  },
  {
    "name": "Bar",
    "schema": [
      {
        "name": "value",
        "type": "string",
        "default": "Lorem Ipsum Default Bar Value"
      }
    ]
  }
]

```

_Note:_: Run `bin/popo generate -c tests/fixtures/.popo case1` to generate files from this example.


### Case 2: Extend Bar schema from within Foo bundle.
  
Main schema: `Foo`. 

Multiple schema files, multiple bundles.

`Foo` adds `bar` as its own property. `Bar` does not need to be modified. 


**`foo/schema/foo.schema.json`** schema:

```json
[
  {
    "name": "Foo",
    "schema": [
      {
        "name": "title",
        "type": "string"
      },
      {
        "name": "bar",
        "type": "Bar",
        "docblock": "Adds Bar property to Foo from Foo bundle"
      }
    ]
  }
]
```

_Note:_: Run `bin/popo generate -c tests/fixtures/.popo case2` to generate files from this example.


### Case 3: Extend Foo schema from within Bar bundle.

Main schema: `Bar`.

Multiple schema files, multiple bundles.


`Bar` injects `bar` into `Foo`. `Foo` does not need to be modified. 


**`bar/schema/foo.schema.json`** schema:

```json
[
  {
    "name": "Foo",
    "schema": [
      {
        "name": "bar",
        "type": "Bar",
        "docblock": "Adds Bar property to Foo from Bar bundle"
      }
    ]
  }
]
```

_Note:_: Run `bin/popo generate -c tests/fixtures/.popo case3` to generate files from this example.

### Result

POPO objects will be generated in the same way, regardless of direction of the dependencies. 

```php
use App\Popo\Foo;

$data = [        
    'title' => 'A title',
    'bar' => [
        'value' => 'Bar lorem ipsum'
    ]
];

$foo = (new Foo)->fromArray($data);

echo $foo->getTitle();
echo $foo->getBar()->getValue();
```

```
A title
Bar lorem ipsum
```

### Displaying configuration

Increase console's verbosity to see configuration settings, eg.

```
bin/popo generate -c tests/fixtures/.popo -v

POPO v3.0.0

+---------------+--------------------------------------------------+
| popo                                                             |
+---------------+--------------------------------------------------+
| schema        | tests/fixtures/popo/                             |
| template      | templates/                                       |
| output        | tests/App/Configurator/                          |
| namespace     | App\Configurator                                 |
| extends       |                                                  |
+---------------+--------------------------------------------------+
| extension     | .php                                             |
| returnType    | self                                             |
+---------------+--------------------------------------------------+
| abstract      | 0                                                |
| withPopo      | 1                                                |
| withInterface | 0                                                |
+---------------+--------------------------------------------------+
>> Generated 5 POPO files for "popo" section
```


See [tests/fixtures/](tests/fixtures/) for more schema examples.
