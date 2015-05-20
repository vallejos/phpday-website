<?php


return function (Silex\Application $app) {
    $config = call_user_func(require __DIR__.'/prod.php', $app);

    $app['twig.options'] = ['cache' => false];

    $app->extend('section_bag', function (\App\SectionBag $bag) {
        $bag->enableSection('speakers');

        return $bag;
    });

    return $config;
};
