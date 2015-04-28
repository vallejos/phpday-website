<?php


return function (Silex\Application $app) {

    $app['twig.path']       = __DIR__.'/../src/App/Resources/views';
    $app['locale_fallback'] = 'es';

    return [];
};
