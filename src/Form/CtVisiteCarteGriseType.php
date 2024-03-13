<?php

namespace App\Form;

use App\Entity\CtCarteGrise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CtVisiteCarteGriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $transport = [
            'Oui' => true,
            'Non' => false
        ];
        $builder
            ->add('ct_centre_id', null, [
                'label' => 'Centre',
                'disabled' => true,
            ])
            ->add('cg_date_emission', null, [
                'label' => 'Date émission',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                //'data' => new \DateTime('now'),
                'disabled' => true,
            ])
            ->add('cg_immatriculation', null, [
                'label' => 'Numéro d\'immatriculation',
            ])
            ->add('cg_num_identification', null, [
                'label' => 'Numéro d\'identification',
                'disabled' => true,
            ])
            ->add('cg_nom', null, [
                'label' => 'Nom propriétaire',
                'disabled' => true,
            ])
            ->add('cg_prenom', null, [
                'label' => 'Prénom propriétaire',
                'disabled' => true,
            ])
            ->add('cg_profession', null, [
                'label' => 'Profession propriétaire',
                'disabled' => true,
            ])
            ->add('cg_adresse', null, [
                'label' => 'Adresse propriétaire',
                'disabled' => true,
            ])
            ->add('cg_phone', null, [
                'label' => 'Téléphone propriétaire',
                'disabled' => true,
            ])
            ->add('cg_commune', null, [
                'label' => 'Commune',
                'disabled' => true,
            ])
            ->add('cg_is_transport', ChoiceType::class, [
                'label' => 'Transport',
                'choices' => $transport,
                'attr' => [
                    'class' => 'istransport',
                ],
                'data' => false,
                'disabled' => true,
            ])
            ->add('cg_num_carte_violette', null, [
                'label' => 'Numéro carte violette',
                'disabled' => true,
            ])
            ->add('cg_lieu_carte_violette', null, [
                'label' => 'Lieu carte violette',
                'disabled' => true,
            ])
            ->add('cg_date_carte_violette', null, [
                'label' => 'Date carte violette',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                //'data' => new \DateTime('now'),
                'disabled' => true,
            ])
            ->add('cg_num_vignette', null, [
                'label' => 'Numéro licence',
                'attr' => [
                    'class' => 'transport',
                ],
                'disabled' => true,
            ])
            ->add('cg_lieu_vignette', null, [
                'label' => 'Lieu numéro licence',
                'attr' => [
                    'class' => 'transport',
                ],
                'disabled' => true,
            ])
            ->add('cg_date_vignette', null, [
                'label' => 'Date numéro licence',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker transport',
                ],
                //'data' => new \DateTime('now'),
                'disabled' => true,
            ])
            ->add('cg_patente', null, [
                'label' => 'Patente',
                'disabled' => true,
            ])
            ->add('ct_carrosserie_id', null, [
                'label' => 'Carrosserie',
                'disabled' => true,
            ])
            ->add('ct_source_energie_id', null, [
                'label' => 'Source d\'energie',
                'disabled' => true,
            ])
            ->add('cg_ani', null, [
                'label' => 'ANI',
                'disabled' => true,
            ])
            ->add('cg_nbr_assis', null, [
                'label' => 'Nombre de place assise',
                'data' => 0,
                'disabled' => true,
            ])
            ->add('cg_mise_en_service', null, [
                'label' => 'Date de première mise en circulation',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                //'data' => new \DateTime('now'),
                'disabled' => true,
            ])
            ->add('ct_vehicule_id', CtVisiteVehiculeType::class, [
                'label' => 'Véhicule',
                'disabled' => true,
            ])
            ->add('cg_nom_cooperative', null, [
                'label' => 'Nom coopérative',
                'attr' => [
                    'class' => 'transport',
                ],
                'disabled' => true,
            ])
            ->add('cg_itineraire', null, [
                'label' => 'Itinéraire',
                'attr' => [
                    'class' => 'transport',
                ],
                'disabled' => true,
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
                'disabled' => true,
            ])
            /* ->add('ct_zone_desserte_id', null, [
                'label' => 'Zone desservie',
                'attr' => [
                    'class' => 'transport',
                ],
                'disabled' => true,
            ]) */
            /* ->add('cg_puissance_admin', null, [
                'label' => 'Puissance administré',
                'data' => 0,
            ]) */

            /* ->add('cg_nbr_debout')
            ->add('cg_rta')
            ->add('cg_created')
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
        ]);
    }
}
