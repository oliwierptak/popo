# POPO Property Name Remapping

`mappingPolicy` and `mappingPolicyValue` options can be used in case where property names nave to be remapped. 

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
      - name: blogTitle
        mappingPolicy:
          - \Popo\Plugin\MappingPolicy\CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME
          - \Popo\Plugin\MappingPolicy\UpperMappingPolicyPlugin::MAPPING_POLICY_NAME

      - name: blogData
        type: popo
        default: BlogData::class
        mappingPolicy:
          - \Popo\Plugin\MappingPolicy\CamelToSnakeMappingPolicyPlugin::MAPPING_POLICY_NAME

      - name: commentThread
        default: ['one','two','three']
        type: array

  BlogData:
    property:
      - name: someValue

  DocumentData:
    property:
      - name: someTitle
        default: Lorem Ipsum
        mappingPolicyValue: some_title

      - name: someValue
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

### Plugins

Mapping policy plugins must implement `Popo\Plugin\MappingPolicy\MappingPolicyPluginInterface`.

See [plugins document](README_PLUGINS.md) for more information.