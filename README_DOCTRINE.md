# POPO Doctrine Support

### Doctrine ORM


The example below generates `Doctrine ORM Entity` and `Doctrine ODM Document` compatible classes.


```yaml
$:
  config:
    outputPath: tests/
    phpComment: |
      Auto-generated.      

# Doctrine ORM Entity example mapping
Entity:
  LogEvent:
    config:
      namespace: App\Example\Entity
      use:
        - App\Repository\LogEventRepository
        - Doctrine\DBAL\Types\Types
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

