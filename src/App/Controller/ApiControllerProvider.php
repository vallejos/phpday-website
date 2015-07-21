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

        $controllers->post('/cfp-webhook', function (Application $app, Request $request) {
            if ($app['slack.options']['outgoing_tokens']['cfp'] !== $request->request->get('token')) {
                return new Response('Go away!', 403);
            } elseif ('cfp: list proposals' === $request->request->get('text')) {
                return $app->json(['text' => $this->getProposalListTexts($app)]);
            } elseif (preg_match('/cfp\: user proposals (?P<email>.+\@.+\..+)$/', $request->request->get('text'), $out)) {
                return $app->json(['text' => $this->getUserProposalsTexts($app, $out['email'])]);
            } elseif (preg_match('/cfp\: user proposals \<mailto\:.+\@.+\..+\|(?P<email>.+\@.+\..+)\>$/', $request->request->get('text'), $out)) {
                return $app->json(['text' => $this->getUserProposalsTexts($app, $out['email'])]);
            }

            return $app->json(['text' => 'Unknown command :boom:']);
        });

        return $controllers;
    }

    private function getProposalListTexts(Application $app)
    {
        $proposals = $app['mongodb']
            ->selectDatabase('phpday')
            ->selectCollection('cfp')
            ->find();

        $listText = '';
        foreach ($proposals as $proposal) {
            $listText .= <<<PROPOSAL
Propuesta recibida de {$proposal['name']}({$proposal['email']})

*{$proposal['title']}*
---------------------------------------------------------------

PROPOSAL;
        }

        return $listText;
    }

    private function getUserProposalsTexts(Application $app, $email)
    {
        $proposals = $app['mongodb']
            ->selectDatabase('phpday')
            ->selectCollection('cfp')
            ->find(['email' => $email]);

        $listText = '';
        foreach ($proposals as $proposal) {
            $description = wordwrap($proposal['description']);
            $listText .= <<<PROPOSAL
*{$proposal['title']}*

{$description}
---------------------------------------------------------------

PROPOSAL;
        }

        return $listText;
    }
}
