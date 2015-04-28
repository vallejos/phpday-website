<?php

namespace App;

use Silex\WebTestCase as BaseTestCase;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * Class WebTestCase.
 */
abstract class WebTestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return HttpKernel
     */
    public function createApplication()
    {
        $app = new PhpDayApplication(__DIR__.'/../../..', 'test', true);

        $app['exception_handler']->disable();

        return $app;
    }
}
