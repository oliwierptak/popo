# About
POPO - Plain Old Php Object is a PHP implementation of "a Plain Old Java Object (POJO)".

POPO will scan, load, validate, merge schema and generate source code files.
The schema supports inheritance and encapsulation of other POPO objects.

POPO generated classes are not bound by any special restriction and not requiring any class path. 
They have a no-argument constructor, and allow access to properties using getter and setter methods,
that follow simple naming convention.
That is by default, because everything can be configured.


#### Example Schema
A simple schema representing two value objects, `Foo` and `Bar`, where `Foo` is using `Bar`.
```
...
  {
    "name": "Foo",
    "schema": [
      {
        "name": "foo",
        "type": "string"
      },
      {
        "name": "bar",
        "type": "Bar"
      },
    ]
  },
  {
    "name": "Bar",
    "schema": [
      {
        "name": "value",
        "type": "string"
      }
    ]
  }
...  
```
#### Generated Code Usage
```
$popo = (new App\Generated\Foo())
    ->fromArray([
        'foo' => 'Foo'
        'bar' => [
            'value' => 'Bar lorem ipsum'
        ]
    ]);
    
echo $popo->getFoo();
echo $popo->getBar()->getValue();
```

The example will output 
```
Foo
Bar lorem ipsum
```

## Installation
With composer:

`composer require popo/generator`


## Usage
If you define your own `.popo` file, you can just call `vendor/bin/popo popo` or `vendor/bin/popo dto` to generate / regenerate your POPO files.

### Configuration with .popo file
Create `.popo` file in project directory, for example:
```
[popo]
schema = popo/
template = vendor/popo/generator/templates/
output = src/YourProject/Popo/
namespace = YourProject\Popo
extension = .php
abstract = false

[dto]
schema = dto
template = vendor/popo/generator/templates/
output = src/YourProject/Popo/
namespace = YourProject\Popo
extension = .php
abstract = false
```

See `popo.dist`.

## Schema Directory Structure
Root directory for all schema files is stored in BuilderConfigurator's `schemaDirectory`.
The GLOB pattern used to scan this directory is stored in SchemaConfigurator's `schemaPath`.
You can always change the default structure and the pattern via `SchemaConfigurator`.

#### Example Schema Configuration for project directory
```
popo/foo/schema/foo.json.schema
popo/bar/schema/bar.json.schema
popo/buzz/schema/buzz.json.schema
```

The root directory for all schema files would be `project/schema/`,
and GLOB patten would be `@^(.*)project/schema/(.*)$@`.


#### Example Schema Configuration for vendor directory

```
vendor/aVendorName/Foo/schema/foo.json.schema
vendor/aVendorName/Bar/schema/bar.json.schema
vendor/aVendorName/Buzz/schema/buzz.json.schema
```

The root directory for all schema files would be `vendor/myVendorName/`,
and GLOB patten would be `@^(.*)vendor/aVendorName/(.*)/schema/(.*)$@`.


## PopoFacade
POPO can generate Data Transfer Objects or simple Popo objects ouf of the box.
It uses `BuilderConfigurator` in order to configure the Builder classes.

To generate simple Popo object, use `generatePopo` method of `PopoFacade`.

```
$facade = new PopoFacade();
$configurator = new BuilderConfigurator();

$facade->generatePopo($configurator);
```


To generate Data Transfer Objects and their interfaces, use `generateDto` method.

```
$facade = new PopoFacade();
$configurator = new BuilderConfigurator();

$facade->generateDto($configurator);
```

To generate string, use `generatePopoString()`, `generateDtoString()` or `generateDtoInterfaceString()` methods.

```
$facade = new PopoFacade();

$configurator = (new BuilderConfigurator())
    ->setTemplateDirectory('templates/')
    ->setNamespace('App\Generated');

$schemaData = [
    'name' => 'Foo',
    'schema' => [[
        'name' => 'fooId',
        'type' => 'int',
    ]],
];

$schema = new Schema($schemaData);
$generatedString = $facade->generatePopoString($configurator, $schema);
```

Check `tests/Popo/PopoFacadeTest.php` for more details.


#### BuilderConfigurator
Use it to configure parameters of source code generator.

```
$configurator = (new BuilderConfigurator())
    ->setSchemaConfigurator(new SchemaConfigurator())
    ->setSchemaDirectory('project/schema/')
    ->setTemplateDirectory('templates/')
    ->setOutputDirectory('src/Generated/')
    ->setNamespace('\Generated')
    ->setExtension('.php');
    ->setSchemaPluginClasses([...])
    ->setPropertyPluginClasses([...])
    ->setIsAbstract(true|false)
    
```

- `SchemaConfigurator`

    Instance of `SchemaConfiguratorInterface`. See below.
    
- `Schema Directory`
    
    Root path where all schema files are located.
    
- `Template Directory`

    Path where the template files are located. 
   
- `Output Directory`

    Path where the generated files should be stored
      
- `Namespace`

    Namespace of generated files.
    
- `Extension`

    File extension of generated files.  
       
- `Abstract`

    Generate abstract popo classes. 
    
- `Schema Plugin Classes`

    Collection of class names implementing `SchemaGeneratorPluginInterface`.
    
    Format:
    ``` 
     [
        SchemaGeneratorPluginInterface::PATTERN => SchemaGeneratorPluginInterface::class,
     ]
    ```
    
- `Property Plugin Classes`

    Collection of class names implementing `PropertyGeneratorPluginInterface`.
    
    Format:
    ``` 
     [
        PropertyGeneratorPluginInterface::PATTERN => PropertyGeneratorPluginInterface::class,
     ]
    ```


#### SchemaConfigurator
Use it to configure schema specific parameters.

```
$schemaConfigurator = (new SchemaConfigurator())
    ->setSchemaPath('@^(.*)project/schema/(.*)$@')
    ->setSchemaFilename('*.schema.json')
    ->setPropertyTemplateFilename('php.property.tpl')
    ->setSchemaTemplateFilename('php.schema.tpl')    
```
    
- `Schema Path`

    GLOB pattern used to find and load all schema files located under schema root path. 

- `Schema Filename`

    GLOB pattern of schema filename.
    
- `Property Template Filename`

    Filename containing contents of template used to generate source code of property related methods.
    See `Templates` section below.
    
- `Schema Template Filename`

    Filename containing contents of template used to generate source code of whole generated file.
    See `Templates` section below. 

### Mandatory options
`Schema Directory`, `Template Directory`, `Output Directory` and `Schema Path`.

Check `BuilderConfigurator` and `SchemaConfigurator` for default values.

## Schema
The schema is very simple JSON file. The `name` and `type` fields are mandatory.
```
{
  "name": "<string>",
  "abstract": "[<bool>]",
  "schema":
    {
      "name": "<string>",
      "type": "<array|bool|float|int|string|popo|mixed>",
      "collectionItem": "[<type>]",
      "singular": "[<string>]",
      "docblock": "[<string>]",
      "default": "[<mixed>]",
      "sourceBundle": "<<runtime only>>"
    }
}
```

### Supported data types

Only primitive data types, with addition of other Popo objects are supported (out of the box).

- array
- boolean
- float
- integer
- string
- mixed
- Popo object


### Naming Conventions
The property name will be used directly as method name (with exception to boolean properties).
 
For example, for property with name `fooBar` and type `string` the following methods will be generated:
- `getFooBar(); ?string` 
- `setFooBar(?string $fooBar): self` 
- `requireFooBar(): string` 


### Naming Conventions for booleans
For `bool` property type, the following methods will be generated:
- `isFooBar(); ?bool` 
- `setIsFooBar(?bool $fooBar): self` 
- `requireIsFooBar(): bool` 

Check `tests/fixtures` directory to see more schema examples.


### Collection support
Besides `array` type, there are two keywords for supporting collections: `collectionItem` and `singular` that can be used to improve array handling.
```
...
  {
    "name": "Foo",
    "schema": [
      {
        "name": "foo",
        "type": "string"
      },
      {
        "name": "bars",
        "type": "array"
      },
      {
        "name": "buzzBars",
        "type": "array"
        "collectionItem" : "\\Popo\\Bar",
        "singular": "buzzBar"
      },
    ]
  },
...  
``` 

Collection example.
```
$popo = (new App\Generated\Foo())
    ->fromArray([
        'foo' => 'Foo'
        'bars' => ['xxx', 'yyy'],
        'buzzBars' => [
            ['value' => 'Lorem ipsum 1']
            ['value' => 'Lorem ipsum 2']
        ]
    ]);
```

Only type was defined as `array`.
```
$popo = (new App\Generated\Foo());
$popo->addBarsItem('xxx');
$popo->addBarsItem('yyy');
```

Example of `collectionItem` and `singular`.
```
$barPopo = (new App\Generated\Bar())
    ->fromArray(['value' => 'Lorem ipsum 1']);

$popo->addBuzzBar($barPopo);
```

### Schema inheritance
To extend schema, just define extra schema file with the same name in another bundle/package/directory.

For example, the `Foo` package is the owner of `foo.schema.json` and `Bar` and `Buzz` packages extend its schema,
by providing extra schema files with `foo.schema.json` filename, as a part of their package/bundle.

```
packages/foo/schema/
 - foo.schema.json
 
packages/bar/schema/
 - bar.schema.json
 - foo.schema.json
 
packages/buzz/schema/
 - buzz.schema.json
 - foo.schema.json
```

## POPO extra methods
Besides getters and setters, there are some helper methods implemented, but if you don't need them,
you can just customize the templates, and remove them. However they tend to be useful.

Sample schema:
```
[
  {
    "name": "Foo",
    "schema": [
      {
        "name": "foo",
        "type": "string"
      },
      {
        "name": "bar",
        "type": "Bar"
      },
    ]
  },
  {
    "name": "Bar",
    "schema": [
      {
        "name": "bar",
        "type": "string"
      }
    ]
  }
]
```

Sample Popo:
```
$popo = (new App\Generated\Foo())
    ->fromArray([
        'foo' => 'Foo lorem ipsum'
        'bar' => ['bar' => 'Bar lorem ipsum']
    ]);
```

#### `public function fromArray(array $data): <self>`
To populate with data.
    

```
echo $popo->getBar()->getBar();
```

Result: `Bar lorem ipsum`


#### `public function toArray(): array`
To convert to `array` recursively.

```
print_r($popo->toArray());
``` 

Result: ```php
[
     'foo' => 'Foo lorem ipsum'
     'bar' => ['bar' => 'Bar lorem ipsum']
 ]
`


#### `public function require<<property name>>(): array`
To throw exception if requested property value is null or return it otherwise.

```
$popo = new App\Generated\Foo();
echo $popo->requireFoo();
``` 

Result: `\UnexpectedValueException('Required value of "Foo" has not been set')`


```
$popo = (new App\Generated\Foo())->setFoo('Foo Lorem Ipsum');
echo $popo->requireFoo();
``` 

Result: `Foo Lorem Ipsum`


## Developing custom plugins
Due to default configuration, POPO will produce PHP source code. 
It could be easily extended to generate any type of code, as long as it follows the schema.

### Generators
There are two generator types. 
`SchemaGenerator` is used to generate the code of whole file, 
and `PropertyGenerator` which is used to generate property methods code. 
They both implement `GeneratorInterface` and have their own template and plugins.

See `tests/Popo/Generator/*` for more details.

#### Generator Plugins
Plugins are used to handle placeholders and their content. 
There are two types.

#### Schema Generator Plugins
Generator plugins that implement `SchemaGeneratorPluginInterface` are responsible for whole source code file.
```
interface SchemaGeneratorPluginInterface extends AcceptPatternInterface
{
    /**
     * Specification:
     * - Uses schema for which the content of <<php.schema.tpl>> will be generated.
     * - Generates string according to schema and configured plugins represented by <<php.schema.tpl>> template.
     * - Returns generated string.
     *
     * @param \Popo\Schema\Reader\SchemaInterface $schema
     *
     * @return string
     */
    public function generate(SchemaInterface $schema): string;
}
```


#### Property Generator Plugins
Generator plugins that implement `PropertyGeneratorPluginInterface` are responsible for property methods source code.
```
interface PropertyGeneratorPluginInterface extends AcceptPatternInterface
{
    /**
     * Specification:
     * - Uses property for which the content of <<php.property.tpl>> will be generated.
     * - Generates string according to schema and configured plugins represented by <<php.property.tpl>> template.
     * - Returns generated string.
     *
     * @param \Popo\Schema\Reader\PropertyInterface $property
     *
     * @return string
     */
    public function generate(PropertyInterface $property): string;
}
```


### Registering Plugins
You can register your own plugins with `GeneratorBuilderInterface` during the building of generator by
providing custom instance of `PluginContainerInterface`.
```
interface GeneratorBuilderInterface
{
    /**
     * Specification:
     * - Loads content of <<php.schema.tpl>> using template directory of configurator
     * - Loads content of <<php.property.tpl>> using template directory of configurator
     * - Creates default property plugin collection and merges it with plugin container
     * - Creates default schema plugin collection and merges it with plugin container
     * - Creates Schema generator
     * - Returns created SchemaGenerator instance
     *
     * @param \Popo\Builder\BuilderConfiguratorInterface $configurator
     * @param \Popo\Builder\PluginContainerInterface $pluginContainer
     *
     * @return \Popo\Generator\GeneratorInterface
     */
    public function build(BuilderConfiguratorInterface $configurator, PluginContainerInterface $pluginContainer): GeneratorInterface;
}
```
Use `registerSchemaClassPlugins(..)` to register your own schema plugins.
 
Use `registerPropertyClassPlugins(...)` to register your own property plugins.

```
$pluginContainer = new PluginContainer(
    new PropertyExplorer()
);
$pluginContainer->registerSchemaClassPlugins([
    FooSchemaGeneratorPlugin::PATTERN => FooSchemaGeneratorPlugin::class,
    BarSchemaGeneratorPlugin::PATTERN => BarSchemaGeneratorPlugin::class,
]);
$pluginContainer->registerPropertyClassPlugins([
    BuzzPropertyGeneratorPlugin::PATTERN => BuzzPropertyGeneratorPlugin::class,
]);
```
See `PluginContainerInterface` for more details.

#### FooSchemaGeneratorPlugin example
`FooSchemaGeneratorPlugin` plugin replaces value of placeholder `<<PROPERTY_NAME>>` with actual property name. 
```
class FooSchemaGeneratorPlugin extends AbstractGeneratorPlugin implements PropertyGeneratorPluginInterface
{
    const PATTERN = '<<PROPERTY_NAME>>';

    public function generate(PropertyInterface $property): string
    {
        return $property->getName();
    }
}
``` 


## Templates
The templates are located by default in `templates/` directory and only two of them are required. 

One is used to generate whole source file, the other is used to generate
each individual method related to properties (setter, getters, etc).

They can be in whatever format and contain whatever content, 
as long as their placeholders are recognized by plugins. 

### Schema template example
Default filename: `php.schema.tpl`.

```
<?php

namespace <<NAMESPACE>>;

<<ABSTRACT>>class <<CLASSNAME>> <<IMPLEMENTS_INTERFACE>>
{
    /**
     * @var array
     */
    protected $data = <<SCHEMA_DATA>>;

    /**
     * @param string $property
     *
     * @return mixed|null
     */
    protected function getValue(string $property)
    {
        if (!isset($this->data[$property])) {
            return null;
        }

        return $this->data[$property];
    }

    /**
     * @param string $property
     * @param mixed $value
     *
     * @return void
     */
    protected function setValue(string $property, $value): void
    {
        $this->data[$property] = $value;
    }

    <<METHODS>>
}
```

### Property template example
Default filename: `php.property.tpl`.

This template is used for each of the defined properties,
and it will replace `<<METHODS>>` placeholder in the schema template above.  

```

    /**
     * @return <<GET_METHOD_RETURN_DOCKBLOCK>>
     */
    public function <<GET_METHOD_NAME>>()<<GET_METHOD_RETURN_TYPE>>
    {
        return $this->getValue('<<PROPERTY_NAME>>');
    }

    /**
     * @param <<SET_METHOD_PARAM_DOCKBLOCK>>
     *
     * @return <<SET_METHOD_RETURN_DOCKBLOCK>>
     */
    public function <<SET_METHOD_NAME>>(<<SET_METHOD_PARAMETERS>>)<<SET_METHOD_RETURN_TYPE>>
    {
        $this->setValue('<<PROPERTY_NAME>>', $<<PROPERTY_NAME>>);

        return $this;
    }

```

#### Customizing Templates
Copy the default templates somewhere, and set it up in the configurator as template directory.


## Tests
Run `vendor/bin/phpunit`. 

The files are generated under `tests/App/Generated`.
The schema files are under `tests/fixtures/bundles`.
The tests are under `tests/Popo`.
