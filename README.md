## Auth Controller

Контролеры для авторизации

https://elastica.io/projects/

https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-elasticsearch-on-centos-7

# Проверить версию эластика

```bash
curl -X GET "localhost:9200/"
```

## Подключения в composer.json

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/webnitros/auth"
    }
  ],
  "require": {
    "webnitros/auth": "^1.0.0"
  }
}
```

### Генератор описания для Facade класса

```php
try {
    FacadePHPDocs::make('Имя класса')->generate();
} catch (ReflectionException $e) {
    echo $e->getMessage() . PHP_EOL;
}
```
