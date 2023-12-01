<?php

namespace App\Form;

use App\Entity\CtBordereau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtBordereauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bl_numero', null, [
                'label' => 'Numéro',
            ])
            ->add('bl_debut_numero', null, [
                'label' => 'Début numéro',
                'data' => 0,
            ])
            ->add('bl_fin_numero', null, [
                'label' => 'Fin numéro',
                'data' => 0,
            ])
            ->add('bl_created_at', null, [
                'label' => 'Date création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('bl_updated_at', null, [
                'label' => 'Date modification',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('ref_expr', null, [
                'label' => 'Référence',
            ])
            ->add('date_ref_expr', null, [
                'label' => 'Date reférence',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('bl_observation', null, [
                'label' => 'Observation',
            ])
            ->add('ct_centre_id', null, [
                'label' => 'Centre',
            ])
            ->add('ct_imprime_tech_id', null, [
                'label' => 'Imprimé technique',
            ])
            ->add('ct_user_id', null, [
                'label' => 'Utilisateur',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtBordereau::class,
        ]);
    }
}
