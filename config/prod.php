<?php

use Symfony\Component\Yaml\Yaml;

return function (Silex\Application $app) {

    $app['twig.path'] = __DIR__.'/../src/App/Resources/views';
    $app['twig.options'] = ['cache' => __DIR__.'/../var/cache/twig'];

    $app['locale_fallback'] = 'es';

    $parameterFile = __DIR__.'/parameters.php';
    if (!is_readable($parameterFile)) {
        throw new RuntimeException('Configuration file not found.');
    }

    $parameters = require $parameterFile;

    $app['mongodb.options'] = [
        'server' => 'mongodb://localhost:27017',
        'options' => [
            'username' => $parameters['database']['user'],
            'password' => $parameters['database']['password'],
            'db' => $parameters['database']['name'],
        ],
    ];

    $app['slack.options'] = $parameters['slack'];
    $app['swiftmailer.options'] = $parameters['mailer'];

    $app['speakers'] = Yaml::parse(file_get_contents(__DIR__.'/speakers.yml', LOCK_EX));
    $app['schedule_info'] = Yaml::parse(file_get_contents(__DIR__.'/schedule.yml', LOCK_EX));

    $updatedTimeFile = __DIR__.'/../var/cache/build-time.php';
    if (!is_readable($updatedTimeFile)) {
        file_put_contents($updatedTimeFile, sprintf('<?php return %d;', time()));
    }

    $app['build_time'] = include_once $updatedTimeFile;

    return [];
};
