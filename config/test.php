<?php


return function (Silex\Application $app) {
    $config = call_user_func(require __DIR__.'/dev.php', $app);

    return $config;
};
