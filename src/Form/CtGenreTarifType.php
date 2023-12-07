<?php

namespace App\Form;

use App\Entity\CtGenreTarif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtGenreTarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('grt_prix', null, [
                'label' => 'Prix',
            ])
            ->add('grt_annee', null, [
                'label' => 'Année',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('ct_genre_id', null, [
                'label' => 'Genre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtGenreTarif::class,
        ]);
    }
}
