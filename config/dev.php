<?php


return function (Silex\Application $app) {
    $config = call_user_func(require __DIR__.'/prod.php', $app);

    return $config;
};
