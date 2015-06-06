<?php

namespace App\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiControllerProvider.
 */
class ApiControllerProvider implements ControllerProviderInterface
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

        $controllers->post('/cfp-webhook', function (
            Application $app,
            Request $request
        ) {
            if ($app['slack.options']['outgoing_tokens']['cfp'] !== $request->request->get('token')) {
                return new Response('Go away!', 403);
            }

            $proposals = $app['mongodb']
                ->selectDatabase('phpday')
                ->selectCollection('cfp')
                ->find();

            $listText = '';
            foreach ($proposals as $proposal) {
                $listText .= <<<PROPOSAL
Propuesta recibida de {$proposal['name']}({$proposal['email']})

{$proposal['title']}

{$proposal['description']}
---------------------------------------------------------------

PROPOSAL;
            }

            return $app->json(['text' => $listText]);
        });

        return $controllers;
    }
}
