<?php

namespace App\Form;

use App\Entity\CtImprimeTechUse;
use App\Entity\CtImprimeTech;
use App\Repository\CtImprimeTechRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use DateTime;

class CtImprimeTechUseDisableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $disable = $options["disable"];
        $visible = [
            'Oui' => true,
            'Non' => false
        ];
        $utiliser = [
            'Oui' => true,
            'Non' => false
        ];
        $differe = [
            'Oui' => true,
            'Non' => false
        ];
        $builder
            ->add('ct_controle_id', null, [
                'label' => 'N° controle / N° enregistrement',
                'disabled' => $disable,
            ])
            ->add('itu_numero', null, [
                'label' => 'N° de l\'imprimé technique',
                'disabled' => True,
            ])
            /* ->add('itu_used', ChoiceType::class, [
                'label' => 'Imprimé technique utilisé',
                'choices' => $utiliser,
                'disable' => true,
            ]) */
            /* ->add('actived_at', DateTimeType::class, [
                'label' => 'Date d\'activation',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
            ]) */
            /* ->add('created_at', DateTimeType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
            ]) */
            /* ->add('updated_at', DateTimeType::class, [
                'label' => 'Date de modification',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
            ]) */
            /* ->add('itu_observation', null, [
                'label' => 'Observation',
            ]) */
            /* ->add('itu_is_visible', ChoiceType::class, [
                'label' => 'Est-visible',
                'choices' => $visible,
            ]) */
            ->add('ct_bordereau_id', null, [
                'label' => 'Bordereau d\'envoi',
                'disabled' => True,
            ])
            /* ->add('ct_centre_id', null, [
                'label' => 'Centre',
            ]) */
            ->add('ct_imprime_tech_id', null, [
                'label' => 'Type imprimé technique',
                'disabled' => True,
            ])
            /* ->add('ct_user_id', null, [
                'label' => 'Utilisateur',
            ]) */
            ->add('ct_usage_it_id', null, [
                'label' => 'Motif d\'utilisation IT',
                'disabled' => $disable,
            ])
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
                'disabled' => $disable,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtImprimeTechUse::class,
            'disable' => false,
        ]);
        $resolver->setRequired([
            'disable',
        ]);
    }
}
