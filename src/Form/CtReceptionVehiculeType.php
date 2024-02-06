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
                'required'   => true,
            ])
            ->add('ct_marque_id', null, [
                'label' => 'Marque véhicule',
                'required'   => true,
            ])
            ->add('vhc_type', null, [
                'label' => 'Type véhicule',
                'required'   => true,
            ])
            ->add('vhc_num_serie', null, [
                'label' => 'Numéro dans la série du type',
                'required'   => true,
            ])
            ->add('vhc_num_moteur', null, [
                'label' => 'Numéro du moteur',
                'required'   => true,
            ])
            ->add('vhc_cylindre', null, [
                'label' => 'Cylindrée',
                'required'   => true,
            ])
            ->add('vhc_puissance', null, [
                'label' => 'Puissance',
                'required'   => true,
            ])
            ->add('vhc_poids_vide', null, [
                'label' => 'Poids à vide',
                'required'   => true,
                'attr' => [
                    'class' => 'vhc_pav',
                ],
            ])
            ->add('vhc_charge_utile', null, [
                'label' => 'Charge utile',
                'required'   => true,
                'attr' => [
                    'class' => 'vhc_cu',
                ],
            ])
            ->add('vhc_poids_total_charge', null, [
                'label' => 'Poids total autorisé en charge',
                'attr' => [
                    'class' => 'vhc_ptac',
                ],
                'disabled' => true,
            ])
            ->add('vhc_hauteur', null, [
                'label' => 'Hauteur',
                'required'   => true,
            ])
            ->add('vhc_largeur', null, [
                'label' => 'Largeur',
                'required'   => true,
            ])
            ->add('vhc_longueur', null, [
                'label' => 'Longueur',
                'required'   => true,
            ])
            /* ->add('vhc_created')
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
