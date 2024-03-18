<?php

namespace App\Form;

use App\Entity\CtAutreVente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\CtVisiteExtra;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CtAutreVenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $visible = [
            'Oui' => true,
            'Non' => false
        ];
        /* $active = [
            'Oui' => true,
            'Non' => false
        ]; */
        $builder
            ->add('auv_is_visible', ChoiceType::class, [
                'label' => 'est-visible',
                'choices' => $visible,
                'data' => false,
            ])
            ->add('auv_created_at', DateTimeType::class, [
                'label' => 'Date création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('controle_id', null, [
                'label' => 'Controle',
            ])
            ->add('ct_usage_it', null, [
                'label' => 'Utilisation imprimé',
            ])
            ->add('ct_autre_tarif_id', null, [
                'label' => 'Autre tarif',
            ])
            ->add('user_id', null, [
                'label' => 'Secrétaire',
            ])
            ->add('verificateur_id', null, [
                'label' => 'Vérificateur',
            ])
            /* ->add('ct_carte_grise_id', null, [
                'label' => 'Carte grise',
            ]) */
            ->add('ct_centre_id', null, [
                'label' => 'Centre',
            ])
            ->add('auv_extra', EntityType::class, [
                'label' => 'Extra',
                'class' => CtVisiteExtra::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('auv_validite', null, [
                'label' => 'Validité',
            ])
            ->add('auv_itineraire', null, [
                'label' => 'Itinéraire',
            ])
            ->add('auv_num_pv', null, [
                'label' => 'Numéro PV',
            ])
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtAutreVente::class,
        ]);
    }
}
