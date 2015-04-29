<?php

require __DIR__.'/../vendor/autoload.php';

(new \App\PhpDayApplication(__DIR__.'/..', 'prod', false))->run();
