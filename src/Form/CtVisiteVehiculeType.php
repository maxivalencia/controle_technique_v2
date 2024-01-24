<?php

namespace App\Form;

use App\Entity\CtVehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtVisiteVehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ct_genre_id', null, [
                'label' => 'Genre véhicule',
                'disabled' => true,
            ])
            ->add('vhc_type', null, [
                'label' => 'Type véhicule',
                'disabled' => true,
            ])
            ->add('ct_marque_id', null, [
                'label' => 'Marque véhicule',
                'disabled' => true,
            ])
            ->add('vhc_num_serie', null, [
                'label' => 'Numéro dans la série du type',
                'disabled' => true,
            ])
            ->add('vhc_num_moteur', null, [
                'label' => 'Numéro du moteur',
                'disabled' => true,
            ])
            ->add('vhc_poids_vide', null, [
                'label' => 'Poids à vide',
                'disabled' => true,
            ])
            ->add('vhc_charge_utile', null, [
                'label' => 'Charge utile',
                'disabled' => true,
            ])
            ->add('vhc_poids_total_charge', null, [
                'label' => 'Poids total à charge',
                'disabled' => true,
            ])
            ->add('vhc_puissance', null, [
                'label' => 'Puissance administré',
                'data' => 0,
                'disabled' => true,
            ])
            ->add('vhc_cylindre', null, [
                'label' => 'Cylindrée',
                'data' => 0,
                'disabled' => true,
            ])

            /* ->add('vhc_hauteur')
            ->add('vhc_largeur')
            ->add('vhc_longueur')
            ->add('vhc_created')
            ->add('vhc_provenance') */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtVehicule::class,
        ]);
    }
}
