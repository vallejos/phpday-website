<?php

namespace App\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Constraints;

/**
 * Class CFPControllerProvider.
 */
class CFPControllerProvider implements ControllerProviderInterface
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

        $controllers->match('/', function (Application $app, Request $request) {
            $flashBag = $app['session']->getFlashBag();

            $form = $this->createForm($app['form.factory']);

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $data['created'] = new \MongoDate();

                $app['mongodb']
                    ->selectDatabase('phpday')
                    ->selectCollection('cfp')
                    ->insert($data);

                $flashBag->add('cfp_messages', [
                    'type' => 'success',
                    'msg' => 'Hemos recibido tu propuesta. Muy pronto serás notidicado sobre los resultados de nuestra selección.',
                ]);

                return new RedirectResponse($app['url_generator']->generate('cfp'));
            }

            return $app['twig']->render('cfp.html.twig', [
                'cfp_form' => $form->createView(),
                'messages' => $flashBag->get('cfp_messages'),
            ]);
        })->method('GET|POST')->bind('cfp');

        return $controllers;
    }

    /**
     * @param FormFactoryInterface $formFactory
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createForm(FormFactoryInterface $formFactory)
    {
        $builder = $formFactory->createNamedBuilder('cfp');

        // Name field
        $builder->add('name', 'text', [
            'attr' => ['placeholder' => 'Juan Gonzalez'],
            'constraints' => [
                new Constraints\NotBlank(['message' => 'Es obligatorio ingresar tu nombre.']),
                new Constraints\Length([
                    'min' => 5,
                    'minMessage' => 'El nombre es muy corto. Debe tener al menos {{ limit }} caracteres.',
                ]),
            ],
        ]);

        // Email field
        $builder->add('email', 'email', [
            'attr' => ['placeholder' => 'juan.gonzalez@example.com'],
            'constraints' => [
                new Constraints\NotBlank(['message' => 'Es obligatorio ingresar tu email.']),
                new Constraints\Email(['message' => 'El email ingresado no es válido.']),
            ],
        ]);

        // Level field
        $builder->add('level', 'choice', [
            'choices' => [
                'inicial' => 'Inicial',
                'intermedio' => 'Intermedio',
                'avanzado' => 'Avanzado',
            ],
            'constraints' => new Constraints\Choice([
                'choices' => ['inicial', 'intermedio', 'avanzado'],
                'message' => 'El valor ingresado no es válido.',
            ]),
        ]);

        // Description field
        $builder->add('title', 'text', [
            'attr' => ['placeholder' => 'Título de la propuesta'],
            'constraints' => [
                new Constraints\NotBlank(['message' => 'Es obligatorio ingresar un título.']),
                new Constraints\Length([
                    'min' => 5,
                    'minMessage' => 'El título es muy corto. Debe tener al menos {{ limit }} caracteres.',
                ]),
            ],
        ]);

        // Description field
        $builder->add('description', 'textarea', [
            'attr' => ['placeholder' => 'Esta charla trata sobre...'],
            'constraints' => new Constraints\NotBlank([
                'message' => 'Es obligatorio ingresar una descripción.',
            ]),
        ]);

        return $builder->getForm();
    }
}
