<?php

namespace App\Form;

use App\Entity\CtImprimeTech;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtImprimeTechType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_imprime_tech', null, [
                'label' => 'Nom imprimé technique',
            ])
            ->add('ute_imprime_tech', null, [
                'label' => 'Unité imprimé technique',
            ])
            ->add('abrev_imprime_tech', null, [
                'label' => 'Abréviation imprimé technique',
            ])
            ->add('prtt_created_at', null, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('prtt_updated_at', null, [
                'label' => 'Date de modification',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('ct_user_id', null, [
                'label' => 'Utilisateur',
            ])
            ->add('ct_type_imprime_id', null, [
                'label' => 'Type de l\'imprimé technique',
                'mapped' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtImprimeTech::class,
        ]);
    }
}
