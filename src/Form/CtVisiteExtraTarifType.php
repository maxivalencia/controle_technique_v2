<?php

namespace App\Form;

use App\Entity\CtVisiteExtraTarif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtVisiteExtraTarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vet_annee', null, [
                'label' => 'Année',
            ])
            ->add('vet_prix', null, [
                'label' => 'Prix',
            ])
            ->add('ct_imprime_tech_id', null, [
                'label' => 'Imprimé technique',
            ])
            ->add('ct_arrete_prix_id', null, [
                'label' => 'Arrêté',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtVisiteExtraTarif::class,
        ]);
    }
}
