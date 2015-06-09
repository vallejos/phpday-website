<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface as Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints;

/**
 * Class CFPType.
 */
class CFPType extends AbstractType
{
    public function finishView(FormView $view, Form $form, array $options)
    {
        $view['country']->vars['noted'] = true;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var TranslatorInterface $translator */
        $translator = $options['translator'];

        // Name field
        $builder->add('name', 'text', [
            'attr' => ['placeholder' => $translator->trans('cfp.short_help.name')],
            'constraints' => [
                new Constraints\NotBlank(['message' => $translator->trans('cfp.errors.name_blank')]),
                new Constraints\Length([
                    'min' => 5,
                    'minMessage' => $translator->trans('cfp.errors.name_short'),
                ]),
            ],
        ]);

        // Email field
        $builder->add('email', 'email', [
            'attr' => ['placeholder' => $translator->trans('cfp.short_help.email')],
            'constraints' => [
                new Constraints\NotBlank(['message' => $translator->trans('cfp.errors.email_blank')]),
                new Constraints\Email(['message' => $translator->trans('cfp.errors.email_invalid')]),
            ],
        ]);

        $regionBundle = Intl::getRegionBundle();

        // Country field
        $builder->add('country', 'choice', [
            'choices' => [
                'UY' => $regionBundle->getCountryName('UY'),
                'AR' => $regionBundle->getCountryName('AR'),
                'BR' => $regionBundle->getCountryName('BR'),
                'other' => $translator->trans('cfp.other_country'),
            ],
            'preferred_choices' => ['UY'],
        ]);

        // Twitter field
        $builder->add('twitter', new TwitterType(), [
            'required' => false,
            'attr' => ['placeholder' => $translator->trans('cfp.short_help.twitter')],
        ]);

        // Level field
        $builder->add('level', 'choice', [
            'choices' => [
                'inicial' => $translator->trans('cfp.level.basic'),
                'intermedio' => $translator->trans('cfp.level.intermediate'),
                'avanzado' => $translator->trans('cfp.level.advanced'),
            ],
            'constraints' => new Constraints\Choice([
                'choices' => ['inicial', 'intermedio', 'avanzado'],
                'message' => $translator->trans('cfp.errors.level_invalid'),
            ]),
        ]);

        // Title field
        $builder->add('title', 'text', [
            'attr' => ['placeholder' => $translator->trans('cfp.short_help.title')],
            'constraints' => [
                new Constraints\NotBlank(['message' => $translator->trans('cfp.errors.title_blank')]),
                new Constraints\Length([
                    'min' => 5,
                    'minMessage' => $translator->trans('cfp.errors.title_short'),
                ]),
            ],
        ]);

        // Description field
        $builder->add('description', 'textarea', [
            'attr' => ['placeholder' => $translator->trans('cfp.short_help.description')],
            'constraints' => new Constraints\NotBlank([
                'message' => $translator->trans('cfp.errors.description_blank'),
            ]),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['translator' => null]);
        $resolver->setAllowedTypes('translator', ['Symfony\Component\Translation\TranslatorInterface']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'cfp';
    }
}
