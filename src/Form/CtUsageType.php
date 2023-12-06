<?php

namespace App\Form;

use App\Entity\CtUsage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtUsageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('usg_libelle', null, [
                'label' => 'Libellé',
            ])
            ->add('usg_validite', null, [
                'label' => 'Validité',
                'data' => 0,
            ])
            ->add('usg_created', null, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('ct_type_usage_id', null, [
                'label' => 'Type d\'utilisation',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtUsage::class,
        ]);
    }
}
