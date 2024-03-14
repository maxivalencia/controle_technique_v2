<?php

namespace App\Form;

use App\Entity\CtAnomalie;
use App\Entity\CtVisite;
use App\Entity\CtVisiteExtra;
use App\Entity\CtImprimeTech;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CtVisiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $apte = [
            'Oui' => true,
            'Non' => false
        ];
        $contre = [
            'Oui' => true,
            'Non' => false
        ];        
        $active = [
            'Oui' => true,
            'Non' => false
        ];
        $builder
            ->add('vst_num_pv', null, [
                'label' => 'Numéro de pv',
            ])
            ->add('vst_num_feuille_caisse', null, [
                'label' => 'Numéro feuille de caisse',
            ])
            ->add('vst_date_expiration', null, [
                'label' => 'Date d\'expiration',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('vst_created', DateTimeType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('vst_updated', null, [
                'label' => 'Date de modification',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('vst_is_apte', ChoiceType::class, [
                'label' => 'Est-apte',
                'choices' => $apte,
                'data' => false,
            ])
            ->add('vst_is_contre_visite', ChoiceType::class, [
                'label' => 'Est-contre visite',
                'choices' => $contre,
                'data' => false,
            ])
            ->add('vst_duree_reparation', null, [
                'label' => 'Durée de reparation',
            ])
            ->add('vst_is_active', ChoiceType::class, [
                'label' => 'Est-active',
                'choices' => $active,
                'data' => false,
            ])
            ->add('vst_genere', null, [
                'label' => 'Généré',
                'data' => 0,
            ])
            ->add('vst_observation', null, [
                'label' => 'Observation',
            ])
            ->add('ct_carte_grise_id', null, [
                'label' => 'Carte grise',
            ])
            ->add('ct_centre_id', null, [
                'label' => 'Centre',
            ])
            ->add('ct_type_visite_id', null, [
                'label' => 'Type de visite',
            ])
            ->add('ct_usage_id', null, [
                'label' => 'Usage',
            ])
            ->add('ct_user_id', null, [
                'label' => 'Utilisateur',
            ])
            ->add('ct_verificateur_id', null, [
                'label' => 'Vérificateur',
            ])
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
            ])
            ->add('vst_anomalie_id', EntityType::class, [
                'label' => 'Anomalies',
                'class' => CtAnomalie::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('vst_extra', EntityType::class, [
                'label' => 'Extra',
                'class' => CtVisiteExtra::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('vst_imprime_tech_id', EntityType::class, [
                'label' => 'Extra',
                'class' => CtImprimeTech::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtVisite::class,
        ]);
    }
}
