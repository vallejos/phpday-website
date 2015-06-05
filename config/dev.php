<?php

return function (Silex\Application $app) {
    $config = call_user_func(require __DIR__.'/prod.php', $app);

    $app['twig.options'] = ['cache' => false];

    $app->extend('slack', function (\App\Notification\Slack $slack) {
        $slack->disable();

        return $slack;
    });

    return $config;
};
