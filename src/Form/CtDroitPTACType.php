<?php

namespace App\Form;

use App\Entity\CtDroitPTAC;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtDroitPTACType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dp_prix_min', null, [
                'label' => 'Prix minimum',
            ])
            ->add('dp_prix_max', null, [
                'label' => 'Prix maximum',
            ])
            ->add('dp_droit', null, [
                'label' => 'Droit',
            ])
            ->add('ct_genre_categorie_id', null, [
                'label' => 'Catégorie du genre',
            ])
            ->add('ct_type_droit_ptac_id', null, [
                'label' => 'Type du droit PTAC',
            ])
            ->add('ct_arrete_prix_id', null, [
                'label' => 'Arrêté prix',
            ])
            ->add('ct_type_reception_id', null, [
                'label' => 'Type réception',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtDroitPTAC::class,
        ]);
    }
}
