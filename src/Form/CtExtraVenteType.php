<?php

namespace App\Form;

use App\Entity\CtExtraVente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CtExtraVenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $active = [
            'Oui' => true,
            'Non' => false
        ];
        $builder
            ->add('exv_created_at', DateTimeType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('exv_is_active', ChoiceType::class, [
                'label' => 'Est-active',
                'choices' => $active,
                'data' => false,
            ])
            ->add('ct_visite_id', null, [
                'label' => 'Visite',
            ])
            ->add('ct_visite_extra_id', null, [
                'label' => 'Visite extra',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtExtraVente::class,
        ]);
    }
}
