<?php

namespace App\Form;

use App\Entity\CtCarteGrise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CtRensCarteGriseType extends AbstractType
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
            ])
            ->add('cg_date_emission', null, [
                'label' => 'Date émission',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('cg_immatriculation', null, [
                'label' => 'Numéro d\'immatriculation',
            ])
            ->add('cg_num_identification', null, [
                'label' => 'Numéro d\'identification',
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
            ->add('cg_is_transport', ChoiceType::class, [
                'label' => 'Transport',
                'choices' => $transport,
                'data' => false,
            ])
            ->add('cg_num_carte_violette', null, [
                'label' => 'Numéro carte violette',
            ])
            ->add('cg_lieu_carte_violette', null, [
                'label' => 'Lieu carte violette',
            ])
            ->add('cg_date_carte_violette', null, [
                'label' => 'Date carte violette',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('cg_patente', null, [
                'label' => 'Patente',
            ])
            ->add('ct_carrosserie_id', null, [
                'label' => 'Carrosserie',
            ])
            ->add('ct_source_energie_id', null, [
                'label' => 'Source d\'energie',
            ])
            ->add('cg_ani', null, [
                'label' => 'ANI',
            ])
            ->add('cg_nbr_assis', null, [
                'label' => 'Nombre de place assise',
                'data' => 0,
            ])
            ->add('cg_mise_en_service', null, [
                'label' => 'Date de première mise en circulation',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            /* ->add('cg_puissance_admin', null, [
                'label' => 'Puissance administré',
                'data' => 0,
            ]) */
            
            /* ->add('cg_nbr_debout')
            ->add('cg_rta')
            ->add('cg_num_vignette')
            ->add('cg_date_vignette')
            ->add('cg_lieu_vignette')
            ->add('cg_created')
            ->add('cg_nom_cooperative')
            ->add('cg_itineraire')
            ->add('cg_is_active')
            ->add('cg_observation')
            ->add('ct_vehicule_id')
            ->add('ct_user_id')
            ->add('ct_zone_desserte_id')
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
