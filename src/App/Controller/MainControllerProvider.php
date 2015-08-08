<?php

namespace App\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
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
        })->bind('homepage');

        $controllers->get('/schedule', function (Application $app) {
            return $app['twig']->render('schedule.html.twig');
        })->bind('schedule2');

        $controllers->get('/templates/{section}', function (Application $app, $section) {
            try {
                return $app['twig']->render(sprintf('%s.html.twig', $section));
            } catch (\Twig_Error_Loader $e) {
                throw new NotFoundHttpException('Page not found.', $e);
            }
        });

        $codeOfConduct = function (Application $app, Request $request) {
            switch ($request->attributes->get('_locale')) {
                case 'es':
                    return $app['twig']->render('codigo-de-conducta.html.twig');
                default:
                    return $app['twig']->render('code-of-conduct.html.twig');
            }
        };

        $controllers->get('/code-of-conduct', $codeOfConduct)->bind('code_conduct');
        $controllers->get('/codigo-de-conducta', $codeOfConduct)->value('_locale', 'es');

        return $controllers;
    }
}
