<?php

namespace App\Form;

use App\Entity\CtProcesVerbal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtProcesVerbalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pv_type', null, [
                'label' => 'Type',
            ])
            ->add('pv_tarif', null, [
                'label' => 'Tarif',
            ])
            ->add('ct_arrete_prix_id', null, [
                'label' => 'Arrêté',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtProcesVerbal::class,
        ]);
    }
}
