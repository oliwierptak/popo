# POPO Plugins

POPO architecture is fully pluggable, meaning any new or existing functionality can be customized.

There is lots of default plugins, and new ones can be added very easily.

#### Example of plugin setup with PHP:

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

#### Example of plugin setup from command line:


```shell
vendor/bin/popo generate -s popo.yml  --classPluginCollection "Popo\\Plugin\\ClassPlugin\\ConstPropertyClassPlugin"
```

```shell
  -clp, --classPluginCollection[=CLASSPLUGINCOLLECTION]                 Collection of class names for plugins implementing \Popo\Plugin\ClassPluginInterface
  -mpp, --mappingPolicyPluginCollection[=MAPPINGPOLICYPLUGINCOLLECTION] Collection of class names for plugins implementing \Popo\Plugin\MappingPolicyPluginInterface
  -nsp, --namespacePluginCollection[=NAMESPACEPLUGINCOLLECTION]         Collection of class names for plugins implementing \Popo\Plugin\NamespacePluginInterface
  -pfp, --phpFilePluginCollection[=PHPFILEPLUGINCOLLECTION]             Collection of class names for plugins implementing \Popo\Plugin\PhpFilePluginInterface
  -ppp, --propertyPluginCollection[=PROPERTYPLUGINCOLLECTION]           Collection of class names for plugins implementing \Popo\Plugin\PropertyPluginInterface
```

## Additional functionality 

Apart from the typical setters and getters POPO objects have additional helper methods which ease access to, and offer
more insight about the data that they represent.

Some of the methods supported by `class` type plugins:

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

Some of the methods supported by `property` type plugins:

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
        public function run(BuilderPluginInterface $builder, PhpNamespace $namespace): PhpNamespace;
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


See [src/Popo/Plugin](src/Popo/Plugin).

## More Examples

See [fixtures](tests/fixtures/popo.yml) and [tests](tests/suite/App/PopoTest.php) for more usage examples.
