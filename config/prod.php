<?php


return function (Silex\Application $app) {

    $app['twig.path']    = __DIR__.'/../src/App/Resources/views';
    $app['twig.options'] = ['cache' => __DIR__.'/../var/cache/twig'];

    $app['locale_fallback'] = 'es';

    return [];
};
