<?php

namespace App\Form;

use App\Entity\CtCarteGrise;
use App\Entity\CtZoneDesserte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CtRensCarteGriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $disable = $options["disable"];
        $transport = [
            'Oui' => true,
            'Non' => false
        ];
        $builder
            ->add('ct_centre_id', null, [
                'label' => 'Centre',
                'disabled' => $disable,
            ])
            ->add('cg_date_emission', null, [
                'label' => 'Date émission',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                //'data' => new \DateTime('now'),
                'disabled' => $disable,
            ])
            ->add('cg_immatriculation', null, [
                'label' => 'Numéro d\'immatriculation',
                'disabled' => $disable,
            ])
            ->add('cg_num_identification', null, [
                'label' => 'Numéro d\'identification',
                'disabled' => $disable,
            ])
            ->add('cg_nom', null, [
                'label' => 'Nom propriétaire',
                'disabled' => $disable,
            ])
            ->add('cg_prenom', null, [
                'label' => 'Prénom propriétaire',
                'disabled' => $disable,
            ])
            ->add('cg_profession', null, [
                'label' => 'Profession propriétaire',
                'disabled' => $disable,
            ])
            ->add('cg_adresse', null, [
                'label' => 'Adresse propriétaire',
                'disabled' => $disable,
            ])
            ->add('cg_phone', null, [
                'label' => 'Téléphone propriétaire',
                'disabled' => $disable,
            ])
            ->add('cg_commune', null, [
                'label' => 'Commune',
                'disabled' => $disable,
            ])
            ->add('cg_is_transport', ChoiceType::class, [
                'label' => 'Transport',
                'choices' => $transport,
                'attr' => [
                    'class' => 'istransport',
                ],
                //'data' => false,
                'disabled' => $disable,
            ])
            ->add('cg_num_carte_violette', null, [
                'label' => 'Numéro carte violette',
                'disabled' => $disable,
            ])
            ->add('cg_lieu_carte_violette', null, [
                'label' => 'Lieu carte violette',
                'disabled' => $disable,
            ])
            ->add('cg_date_carte_violette', null, [
                'label' => 'Date carte violette',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                //'data' => new \DateTime('now'),
                'disabled' => $disable,
            ])
            ->add('cg_num_vignette', null, [
                'label' => 'Numéro licence',
                'attr' => [
                    'class' => 'transport',
                ],
                'disabled' => $disable,
            ])
            ->add('cg_lieu_vignette', null, [
                'label' => 'Lieu numéro licence',
                'attr' => [
                    'class' => 'transport',
                ],
                'disabled' => $disable,
            ])
            ->add('cg_date_vignette', null, [
                'label' => 'Date numéro licence',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker transport',
                ],
                //'data' => new \DateTime('now'),
                'disabled' => $disable,
            ])
            ->add('cg_patente', null, [
                'label' => 'Patente',
                'disabled' => $disable,
            ])
            ->add('ct_carrosserie_id', null, [
                'label' => 'Carrosserie',
                'disabled' => $disable,
            ])
            ->add('ct_source_energie_id', null, [
                'label' => 'Source d\'energie',
                'disabled' => $disable,
            ])
            ->add('cg_ani', null, [
                'label' => 'ANI',
                'disabled' => $disable,
            ])
            ->add('cg_nbr_assis', null, [
                'label' => 'Nombre de place assise',
                //'data' => 0,
                'disabled' => $disable,
            ])
            ->add('cg_mise_en_service', null, [
                'label' => 'Date de première mise en circulation',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                //'data' => new \DateTime('now'),
                'disabled' => $disable,
            ])
            ->add('ct_vehicule_id', CtRensVehiculeType::class, [
                'label' => 'Véhicule',
                'disabled' => $disable,
            ])            
            ->add('cg_nom_cooperative', null, [
                'label' => 'Nom coopérative',
                'attr' => [
                    'class' => 'transport',
                ],
                'disabled' => $disable,
            ])
            ->add('cg_itineraire', null, [
                'label' => 'Itinéraire',
                'attr' => [
                    'class' => 'transport',
                ],
                'disabled' => $disable,
            ])
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
                'multiple' => false,
                'attr' => [
                    'class' => 'multi select',
                    'multiple' => false,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
                'disabled' => $disable,
            ])
            /* ->add('ct_zone_desserte_id', EntityType::class, [
                'label' => 'Zone desservie',
                'class' => CtZoneDesserte::class,
                'attr' => [
                    'class' => 'transport',
                ],
            ]) */
            /* ->add('cg_puissance_admin', null, [
                'label' => 'Puissance administré',
                'data' => 0,
            ]) */
            
            /* ->add('cg_nbr_debout')
            ->add('cg_rta')
            ->add('cg_created'))
            ->add('cg_is_active')
            ->add('cg_observation')
            ->add('ct_vehicule_id')
            ->add('ct_user_id')
            ->add('cg_antecedant_id') */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtCarteGrise::class,
            'disable' => false,
        ]);
        $resolver->setRequired([
            'disable',
        ]);
    }
}
