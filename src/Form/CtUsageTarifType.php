<?php

namespace App\Form;

use App\Entity\CtUsageTarif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtUsageTarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('usg_trf_annee', null, [
                'label' => 'Année',
            ])
            ->add('usg_trf_prix', null, [
                'label' => 'Prix',
            ])
            ->add('ct_usage_id', null, [
                'label' => 'Utilisation',
            ])
            ->add('ct_type_visite_id', null, [
                'label' => 'Type de visite',
            ])
            ->add('ct_arrete_prix_id', null, [
                'label' => 'Arrêté',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtUsageTarif::class,
        ]);
    }
}
