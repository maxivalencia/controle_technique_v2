<?php

namespace App\Form;

use App\Entity\CtVehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtVehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vhc_cylindre', null, [
                'label' => 'Cylindre',
            ])
            ->add('vhc_puissance', null, [
                'label' => 'Puissance',
            ])
            ->add('vhc_poids_vide', null, [
                'label' => 'Poids à vide',
                'attr' => [
                    'class' => 'vhc_pav',
                ],
            ])
            ->add('vhc_charge_utile', null, [
                'label' => 'Charge utile',
                'attr' => [
                    'class' => 'vhc_cu',
                ],
            ])
            ->add('vhc_hauteur', null, [
                'label' => 'Hauteur',
            ])
            ->add('vhc_largeur', null, [
                'label' => 'Largeur',
            ])
            ->add('vhc_longueur', null, [
                'label' => 'Longueur',
            ])
            ->add('vhc_num_serie', null, [
                'label' => 'Numéro de série',
            ])
            ->add('vhc_num_moteur', null, [
                'label' => 'Numéro du moteur',
            ])
            ->add('vhc_created', null, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('vhc_provenance', null, [
                'label' => 'Provenance',
            ])
            ->add('vhc_type', null, [
                'label' => 'Type',
            ])
            ->add('vhc_poids_total_charge', null, [
                'label' => 'Poids total à charge',
                'attr' => [
                    'class' => 'vhc_ptac',
                ],
            ])
            ->add('ct_genre_id', null, [
                'label' => 'Genre',
            ])
            ->add('ct_marque_id', null, [
                'label' => 'Marque',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtVehicule::class,
        ]);
    }
}
