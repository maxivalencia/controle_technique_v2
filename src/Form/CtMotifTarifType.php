<?php

namespace App\Form;

use App\Entity\CtMotifTarif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtMotifTarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mtf_trf_prix', null, [
                'label' => 'Prix',
            ])
            ->add('mtf_trf_date', null, [
                'label' => 'Date',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('ct_motif_id', null, [
                'label' => 'Motif',
            ])
            ->add('ct_arrete_prix', null, [
                'label' => 'Arrêté',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtMotifTarif::class,
        ]);
    }
}
