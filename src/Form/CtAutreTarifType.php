<?php

namespace App\Form;

use App\Entity\CtAutreTarif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtAutreTarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('aut_prix', null, [
                'label' => 'Prix',
            ])
            ->add('aut_arrete', null, [
                'label' => 'Arrêté',
            ])
            ->add('aut_date', null, [
                'label' => 'Date',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('ct_usage_imprime_technique_id', null, [
                'label' => 'Imprimé technique',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtAutreTarif::class,
        ]);
    }
}
