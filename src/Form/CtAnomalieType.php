<?php

namespace App\Form;

use App\Entity\CtAnomalie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtAnomalieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('anml_libelle', null, [
                'label' => 'Libellé',
            ])
            ->add('anml_code', null, [
                'label' => 'Code',
            ])
            ->add('anml_niveau_danger', null, [
                'label' => 'Niveau de danger',
                'data' => 0,
            ])
            ->add('ct_anomalie_type_id', null, [
                'label' => 'Type d\'anomalie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtAnomalie::class,
        ]);
    }
}
