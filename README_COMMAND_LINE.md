# POPO Command Line Options

## Generate Command

```shell
vendor/bin/popo generate -s <schema.yml> [options...]
```

```shell
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

For example: 
```shell
vendor/bin/popo generate -s popo.yml  --classPluginCollection "Popo\\Plugin\\ClassPlugin\\ConstPropertyClassPlugin"
```


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


## Report Command

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
