<?php

namespace App\Form;

use App\Entity\CtHistorique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CtHistoriqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $vue = [
            'Oui' => true,
            'Non' => false
        ];
        $builder
            ->add('hst_description', null, [
                'label' => 'Description',
            ])
            ->add('hst_date_created_at', DateTimeType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('hst_is_view', ChoiceType::class, [
                'label' => 'Est-vue',
                'choices' => $vue,
                'data' => false,
            ])
            ->add('ct_user_id', null, [
                'label' => 'Utilisateur',
            ])
            ->add('ct_center_id', null, [
                'label' => 'Centre',
            ])
            ->add('ct_historique_type_id', null, [
                'label' => 'Type d\'historique',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtHistorique::class,
        ]);
    }
}
