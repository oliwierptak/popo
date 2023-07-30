# POPO Doctrine Support

### Doctrine ORM


The example below generates `Doctrine ODM Document` compatible classes.


```yaml
$:
  config:
    outputPath: tests/
    phpComment: |
      Auto-generated.      

# Doctrine ODM Document example mapping
Document:
  LogEvent:
    config:
      namespace: App\Example\Document
      use:
        - Doctrine\ODM\MongoDB\Mapping\Annotations\Document
        - Doctrine\ODM\MongoDB\Mapping\Annotations\Field
        - Doctrine\ODM\MongoDB\Mapping\Annotations\Id
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

