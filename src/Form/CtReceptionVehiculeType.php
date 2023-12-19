<?php

namespace App\Form;

use App\Entity\CtVehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtReceptionVehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ct_genre_id', null, [
                'label' => 'Genre véhicule',
            ])
            ->add('ct_marque_id', null, [
                'label' => 'Marque véhicule',
            ])
            ->add('vhc_type', null, [
                'label' => 'Type véhicule',
            ])
            ->add('vhc_num_serie', null, [
                'label' => 'Numéro dans la série du type',
            ])
            ->add('vhc_num_moteur', null, [
                'label' => 'Numéro du moteur',
            ])
            ->add('vhc_cylindre', null, [
                'label' => 'Cylindrée',
            ])
            ->add('vhc_puissance', null, [
                'label' => 'Puissance',
            ])
            ->add('vhc_poids_vide', null, [
                'label' => 'Poids à vide',
            ])
            ->add('vhc_charge_utile', null, [
                'label' => 'Charge utile',
            ])
            ->add('vhc_poids_total_charge', null, [
                'label' => 'Poids total autorisé en charge',
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
