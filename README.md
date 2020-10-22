# POPO

POPO - "Plain Old Php Object" was inspired by "Plain Old Java Object" (POJO) concept.

POPO can also locate, load, validate, and combine JSON schemas to generate PHP source code files.
The schema supports inheritance, collections and encapsulation of other POPO objects.

## Example

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

```php
$foo->getBar()->setValue('new value');
print_r($foo->toArray());
```

Output:

```
'A title'
'Bar lorem ipsum'
```

```php
$updateData = [
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
Define schema, configure and save settings in `.popo` file and run:

```sh
vendor/bin/popo generate
```

See [popo.dist](.popo.dist) for examples.

You can also skip the `.popo` file and provide all settings via the command line. See `--help` for details.


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

_Note_: There can be multiple schema folders, and each can be used as a parameter from a command line or as configuration option.


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

### POPO objects definition

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

After running `generate` there will be two POPO objects ready to be used `Foo` and `Bar`.


### Relations and extending already defined schemas

At this point `Foo` has no idea about `Bar` and vice versa.
However, it could be useful to be able to extend schemas that were already defined. 

To achieve this, you can either `add` or `inject` property.


#### Case 1: Single project / library

One schema file, no bundles.

Main schema: `Foo`.

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


#### Case 2: Extend Bar schema from within Foo bundle.
  
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


#### Case 3: Extend Foo schema from within Bar bundle.

Multiple schema files, multiple bundles.

Main schema: `Bar`.

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

#### Result

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

See [tests/fixtures/](tests/fixtures/) for more schema examples.
