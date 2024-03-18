<?php

namespace App\Form;

use App\Entity\CtImprimeTechUse;
use DateTime;
use Symfony\Component\Form\AbstractType;
use App\Entity\CtImprimeTech;
use App\Repository\CtImprimeTechRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CtImprimeTechUseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $visible = [
            'Oui' => true,
            'Non' => false
        ];
        $utiliser = [
            'Oui' => true,
            'Non' => false
        ];
        $builder
            ->add('ct_controle_id', null, [
                'label' => 'Controle id',
            ])
            ->add('itu_numero', null, [
                'label' => 'Numéro d\'imprimé technique',
            ])
            ->add('itu_used', ChoiceType::class, [
                'label' => 'Imprimé technique utilisé',
                'choices' => $utiliser,
                'data' => false,
            ])
            ->add('actived_at', DateTimeType::class, [
                'label' => 'Date d\'activation',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('created_at', DateTimeType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('updated_at', DateTimeType::class, [
                'label' => 'Date de modification',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('itu_observation', null, [
                'label' => 'Observation',
            ])
            ->add('itu_is_visible', ChoiceType::class, [
                'label' => 'Est-visible',
                'choices' => $visible,
                'data' => false,
            ])
            ->add('ct_bordereau_id', null, [
                'label' => 'Bordereau',
            ])
            ->add('ct_centre_id', null, [
                'label' => 'Centre',
            ])
            ->add('ct_imprime_tech_id', null, [
                'label' => 'Imprimé technique',
            ])
            ->add('ct_user_id', null, [
                'label' => 'Utilisateur',
            ])
            ->add('ct_usage_it_id', null, [
                'label' => 'Utilisation',
            ])
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtImprimeTechUse::class,
        ]);
    }
}
