<?php

namespace App\Form;

use App\Entity\CtReception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtReceptionReceptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
            ])
            ->add('ct_motif_id', null, [
                'label' => 'Motif',
            ])
            ->add('rcp_immatriculation', null, [
                'label' => 'Immatriculation',
            ])
            ->add('rcp_proprietaire', null, [
                'label' => 'Propriétaire',
            ])
            ->add('rcp_profession', null, [
                'label' => 'Profession',
            ])
            ->add('rcp_adresse', null, [
                'label' => 'Adresse',
            ])
            ->add('rcp_nbr_assis', null, [
                'label' => 'Nombre de place assise',
                'data' => 0,
            ])
            ->add('rcp_ngr_debout', null, [
                'label' => 'Nombre de place debout',
                'data' => 0,
            ])
            ->add('ct_source_energie_id', null, [
                'label' => 'Source d\'energie',
            ])
            ->add('ct_carrosserie_id', null, [
                'label' => 'Carrosserie',
            ])
            ->add('rcp_mise_service', null, [
                'label' => 'Date de mise en service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('ct_verificateur_id', null, [
                'label' => 'Vérificateur',
            ])
            ->add('ct_vehicule_id', CtReceptionVehiculeType::class, [
                'label' => 'Véhicule',
            ])
            /* ->add('ct_type_reception_id', null, [
                'label' => 'Type de réception',
            ]) */
            /* ->add('rcp_num_pv')
            ->add('rcp_num_group')
            ->add('rcp_created')
            ->add('rcp_is_active')
            ->add('rcp_genere')
            ->add('rcp_observation')
            ->add('ct_centre_id')
            ->add('ct_user_id')
            ->add('ct_genre_id') */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtReception::class,
        ]);
    }
}
