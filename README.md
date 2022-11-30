## Auth Controller

Контролеры для авторизации

https://elastica.io/projects/

https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-elasticsearch-on-centos-7

Какая та писанина не понятная
https://logz.io/blog/10-elasticsearch-concepts/

# Большая статья дополненеи к suggest
https://habr.com/ru/company/directum/blog/460263/

# Мега подробное обьяснение как работает эластик

https://sohabr.net/habr/post/433006/


## Управление синонемами
https://antonshell.me/post/elastic-search-synonyms

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
      "url": "https://github.com/webnitros/ela"
    }
  ],
  "require": {
    "webnitros/ela": "^1.0.0"
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
