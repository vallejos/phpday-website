<?php

namespace App\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class MainControllerProvider.
 */
class MainControllerProvider implements ControllerProviderInterface
{
    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            return $app['twig']->render('index.html.twig');
        });

        $controllers->get('/templates/{section}', function (Application $app, $section) {
            try {
                return $app['twig']->render(sprintf('%s.html.twig', $section));
            } catch (\Twig_Error_Loader $e) {
                throw new NotFoundHttpException('Page not found.', $e);
            }
        });

        return $controllers;
    }
}
