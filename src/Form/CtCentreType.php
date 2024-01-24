<?php

namespace App\Form;

use App\Entity\CtCentre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtCentreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ctr_nom', null, [
                'label' => 'Nom centre',
            ])
            ->add('ctr_code', null, [
                'label' => 'Code centre',
            ])
            ->add('ctr_created_at', null, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('ctr_updated_at', null, [
                'label' => 'Date de modification',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('ct_province_id', null, [
                'label' => 'Province',
            ])
            ->add('centre_mere', null, [
                'label' => 'Centre mère',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtCentre::class,
        ]);
    }
}
