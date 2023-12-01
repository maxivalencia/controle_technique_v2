<?php

namespace App\Form;

use App\Entity\CtGenreCategorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CtGenreCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $calculable = [
            'Oui' => true,
            'Non' => false
        ];
        $builder
            ->add('gc_libelle', null, [
                'label' => 'Libellé',
            ])
            ->add('gc_is_calculable', ChoiceType::class, [
                'label' => 'Est-calculable',
                'choices' => $calculable,
                'data' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtGenreCategorie::class,
        ]);
    }
}
