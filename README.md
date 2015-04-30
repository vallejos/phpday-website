# Website de phpday.uy

## Configuración

Lo único que hay que hacer es instalar las dependencias del proyecto con _Composer_.

```
$ composer install
```

## Desarrollo

Podemos utilizar el servidor _built-in_ de PHP para poder ejecutar el sitio en nuestra máquina:

```
$ php -S localhost:8000 -t web
```

## Datos útiles

En esta sección se listan algunos datos útiles a tener en cuenta a la hora de realizar cambios.

### Traducciones

Las traducciones se guardan en un archivo YAML en el [directorio de ***Resources***][translations]. 
Es un diccionario clave/valor cuya clave es utilizada en los _templates_ de la siguiente forma:

```twig
<div>{{ 'clave'|trans }}</div>
```

Si el valor para **clave** fuera *foo*, deberíamos ver lo siguiente:

```html
<div>foo</div>
```

### Secciones

Como estamos en pleno desarrollo, tanto del sitio como de la organización, no todas las secciones
pueden estar habilitadas en el sitio. Las diferentes secciones se configuran en la [clase de la aplicación][bag-service]. En caso de queer habilitar una sección solo en desarrollo, podemos hacerlo de la siguiente forma en el [archivo de configuración del entorno *dev*](config/dev.php).

```php
$app->extend('section_bag', function (App\SectionBag $bag) {
    $bag->enableSection('MySection');
    
    return $bag; // Don't forget this!!
});
```

[translations]: https://github.com/PHPmvd/phpday-website/tree/9f41b03169322045d07c80a0b770ddacca5f2015/src/App/Resources/translations
[bag-service]: https://github.com/PHPmvd/phpday-website/blob/9f41b03169322045d07c80a0b770ddacca5f2015/src/App/PhpDayApplication.php#L66-L77