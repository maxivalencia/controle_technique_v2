<?php

namespace App\Form;

use App\Entity\CtConstAvDedCarac;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtConstAvDedCaracType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cad_cylindre', null, [
                'label' => 'Cylindrée',
            ])
            ->add('cad_puissance', null, [
                'label' => 'Puissance administré',
            ])
            ->add('cad_poids_vide', null, [
                'label' => 'Poids à vide',
            ])
            ->add('cad_charge_utile', null, [
                'label' => 'Charge utile',
            ])
            ->add('cad_hauteur', null, [
                'label' => 'Hauteur',
            ])
            ->add('cad_largeur', null, [
                'label' => 'Largeur',
            ])
            ->add('cad_longueur', null, [
                'label' => 'Longueur',
            ])
            ->add('cad_num_serie_type', null, [
                'label' => 'Numéro de serie du type',
            ])
            ->add('cad_num_moteur', null, [
                'label' => 'Numéro moteur',
            ])
            ->add('cad_type_car', null, [
                'label' => 'Type véhicule',
            ])
            ->add('cad_poids_maxima', null, [
                'label' => 'Autre',
            ])
            ->add('cad_poids_total_charge', null, [
                'label' => 'Poids total à charge',
            ])
            ->add('cad_premiere_circule', null, [
                'label' => 'Date de première circulation',
            ])
            ->add('cad_nbr_assis', null, [
                'label' => 'nombre de place assise',
            ])
            ->add('ct_carrosserie_id', null, [
                'label' => 'Carrosserie',
            ])
            ->add('ct_const_av_ded_type_id', null, [
                'label' => 'Constatation avant dedouanement',
            ])
            ->add('ct_genre_id', null, [
                'label' => 'Genre',
            ])
            ->add('ct_marque_id', null, [
                'label' => 'Marque',
            ])
            ->add('ct_source_energie_id', null, [
                'label' => 'Source d\'energie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtConstAvDedCarac::class,
        ]);
    }
}
