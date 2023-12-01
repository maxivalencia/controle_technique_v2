<?php

namespace App\Form;

use App\Entity\CtGenre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtGenreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gr_libelle', null, [
                'label' => 'Libellé',
            ])
            ->add('gr_code', null, [
                'label' => 'Code',
            ])
            ->add('ct_genre_categorie_id', null, [
                'label' => 'Catégorie du genre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtGenre::class,
        ]);
    }
}
