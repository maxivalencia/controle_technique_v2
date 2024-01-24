<?php

namespace App\Form;

use App\Entity\CtPhoto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtPhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ct_controle_id', null, [
                'label' => 'Controle id',
            ])
            ->add('pht_nom', null, [
                'label' => 'Nom',
            ])
            ->add('pht_dossier', null, [
                'label' => 'Dossier',
            ])
            ->add('ct_usage_it', null, [
                'label' => 'Utilisation',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtPhoto::class,
        ]);
    }
}
