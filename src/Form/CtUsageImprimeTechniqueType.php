<?php

namespace App\Form;

use App\Entity\CtUsageImprimeTechnique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtUsageImprimeTechniqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('uit_libelle', null, [
                'label' => 'Libellé',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtUsageImprimeTechnique::class,
        ]);
    }
}
