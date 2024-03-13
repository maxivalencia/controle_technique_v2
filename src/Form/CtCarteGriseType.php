<?php

namespace App\Form;

use App\Entity\CtCarteGrise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CtCarteGriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $active = [
            'Oui' => true,
            'Non' => false
        ];
        $transport = [
            'Oui' => true,
            'Non' => false
        ];
        $builder
            ->add('cg_date_emission', null, [
                'label' => 'Date émission',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('cg_nom', null, [
                'label' => 'Nom propriétaire',
            ])
            ->add('cg_prenom', null, [
                'label' => 'Prénom propriétaire',
            ])
            ->add('cg_profession', null, [
                'label' => 'Profession propriétaire',
            ])
            ->add('cg_adresse', null, [
                'label' => 'Adresse propriétaire',
            ])
            ->add('cg_phone', null, [
                'label' => 'Téléphone propriétaire',
            ])
            ->add('cg_commune', null, [
                'label' => 'Commune',
            ])
            ->add('cg_nbr_assis', null, [
                'label' => 'Nombre de place assise',
                'data' => 0,
            ])
            ->add('cg_nbr_debout', null, [
                'label' => 'Nombre de place debout',
                'data' => 0,
            ])
            ->add('cg_puissance_admin', null, [
                'label' => 'Puissance administré',
                'data' => 0,
            ])
            ->add('cg_mise_en_service', null, [
                'label' => 'Date de mise en service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('cg_patente', null, [
                'label' => 'Patente',
            ])
            ->add('cg_ani', null, [
                'label' => 'ANI',
            ])
            ->add('cg_rta', null, [
                'label' => 'RTA',
            ])
            ->add('cg_num_carte_violette', null, [
                'label' => 'Numéro carte violette',
            ])
            ->add('cg_date_carte_violette', null, [
                'label' => 'Date carte violette',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('cg_lieu_carte_violette', null, [
                'label' => 'Lieu carte violette',
            ])
            ->add('cg_num_vignette', null, [
                'label' => 'Numéro vignette',
            ])
            ->add('cg_date_vignette', null, [
                'label' => 'Date vignette',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('cg_lieu_vignette', null, [
                'label' => 'Lieu vignette',
            ])
            ->add('cg_immatriculation', null, [
                'label' => 'Immatriculation',
            ])
            ->add('cg_created', null, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('cg_nom_cooperative', null, [
                'label' => 'Nom cooperative',
            ])
            ->add('cg_itineraire', null, [
                'label' => 'itinéraire',
            ])
            ->add('cg_is_transport', ChoiceType::class, [
                'label' => 'est-transport',
                'choices' => $transport,
                'data' => false,
            ])
            ->add('cg_num_identification', null, [
                'label' => 'Numéro d\'identification',
            ])
            ->add('cg_is_active', ChoiceType::class, [
                'label' => 'est-active',
                'choices' => $active,
                'data' => false,
            ])
            ->add('cg_observation', null, [
                'label' => 'Observation',
            ])
            ->add('ct_carrosserie_id', null, [
                'label' => 'Carrosserie',
            ])
            ->add('ct_centre_id', null, [
                'label' => 'Centre',
            ])
            ->add('ct_source_energie_id', null, [
                'label' => 'Source d\'energie',
            ])
            ->add('ct_vehicule_id', null, [
                'label' => 'Véhicule',
            ])
            ->add('ct_user_id', null, [
                'label' => 'Utilisateur',
            ])
            ->add('ct_zone_desserte_id', null, [
                'label' => 'Zone desserté',
            ])
            ->add('cg_antecedant_id', null, [
                'label' => 'Antécédant carte grise',
            ])
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtCarteGrise::class,
        ]);
    }
}
