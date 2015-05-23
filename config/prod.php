<?php


return function (Silex\Application $app) {

    $app['twig.path']    = __DIR__.'/../src/App/Resources/views';
    $app['twig.options'] = ['cache' => __DIR__.'/../var/cache/twig'];

    $app['locale_fallback'] = 'es';

    $parameterFile = __DIR__.'/parameters.php';
    if (!is_readable($parameterFile)) {
        throw new RuntimeException('Configuration file not found.');
    }

    $parameters = require $parameterFile;

    $this['mongodb.options'] = [
        'server' => 'mongodb://localhost:27017',
        'options' => [
            'username' => $parameters['database']['user'],
            'password' => $parameters['database']['password'],
            'db' => $parameters['database']['name'],
        ],
    ];

    $this['slack.options'] = $parameters['slack'];

    return [];
};
