<?php

namespace App\Form;

use App\Entity\CtReception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CtReceptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { 
        $active = [
            'Oui' => true,
            'Non' => false
        ];
        $builder
            ->add('rcp_mise_service', null, [
                'label' => 'Date de mise en service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
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
            ->add('rcp_num_pv', null, [
                'label' => 'Numéro procès-verbal',
            ])
            ->add('rcp_num_group', null, [
                'label' => 'Numéro de groupe',
            ])
            ->add('rcp_created', null, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('rcp_is_active', ChoiceType::class, [
                'label' => 'Est-active',
                'choices' => $active,
                'data' => false,
            ])
            ->add('rcp_genere', null, [
                'label' => 'Généré',
                'data' => 0,
            ])
            ->add('rcp_observation', null, [
                'label' => 'Observation',
            ])
            ->add('ct_centre_id', null, [
                'label' => 'Centre',
            ])
            ->add('ct_motif_id', null, [
                'label' => 'Motif',
            ])
            ->add('ct_type_reception_id', null, [
                'label' => 'Type de réception',
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
            ->add('ct_vehicule_id', null, [
                'label' => 'Véhicule',
            ])
            ->add('ct_source_energie_id', null, [
                'label' => 'Source d\'energie',
            ])
            ->add('ct_carrosserie_id', null, [
                'label' => 'Carrosserie',
            ])
            ->add('ct_genre_id', null, [
                'label' => 'Genre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtReception::class,
        ]);
    }
}
