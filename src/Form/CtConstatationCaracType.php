<?php

namespace App\Form;

use App\Entity\CtConstAvDedCarac;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CtConstatationCaracType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cad_premiere_circule', null, [
                'label' => 'Date de la première mise en circulation',
            ])
            ->add('ct_genre_id', null, [
                'label' => 'Genre',
            ])
            ->add('ct_marque_id', null, [
                'label' => 'Marque',
            ])
            ->add('cad_type_car', null, [
                'label' => 'Type',
            ])
            ->add('cad_num_serie_type', null, [
                'label' => 'Numéro dans la serie du type',
            ])
            ->add('cad_num_moteur', null, [
                'label' => 'Numéro du moteur',
            ])
            ->add('ct_source_energie_id', null, [
                'label' => 'Source d\'energie',
            ])
            ->add('cad_cylindre', null, [
                'label' => 'Cylindrée',
            ])
            ->add('cad_puissance', null, [
                'label' => 'Puissance administrative',
            ])
            ->add('ct_carrosserie_id', null, [
                'label' => 'Carrosserie',
            ])
            ->add('cad_nbr_assis', null, [
                'label' => 'Nombre de place assise',
            ])
            ->add('cad_largeur', null, [
                'label' => 'Largeur',
            ])
            ->add('cad_hauteur', null, [
                'label' => 'Hauteur',
            ])
            ->add('cad_longueur', null, [
                'label' => 'Longueur',
            ])
            ->add('cad_charge_utile', null, [
                'label' => 'Charge utile',
                'attr' => [
                    'class' => 'vhc_cu',
                ],
            ])
            ->add('cad_poids_vide', null, [
                'label' => 'Poids à vide',
                'attr' => [
                    'class' => 'vhc_pav',
                ],
            ])
            ->add('cad_poids_total_charge', null, [
                'label' => 'Poids total en charge',
                'attr' => [
                    'class' => 'vhc_ptac',
                ],
                'disabled' => true,
            ])
            /* ->add('cad_poids_maxima')
            ->add('ct_const_av_ded_type_id')
            ->add('ctConstAvDeds') */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtConstAvDedCarac::class,
        ]);
    }
}
