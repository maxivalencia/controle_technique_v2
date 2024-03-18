<?php

namespace App\Form;

use App\Entity\CtConstAvDed;
use App\Entity\CtConstAvDedCarac;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CtConstAvDedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $active = [
            'Oui' => true,
            'Non' => false
        ];
        $etat = [
            'Oui' => true,
            'Non' => false
        ];
        $personne = [
            'Oui' => true,
            'Non' => false
        ];
        $marchandise = [
            'Oui' => true,
            'Non' => false
        ];
        $environnement = [
            'Oui' => true,
            'Non' => false
        ];
        $conforme = [
            'Oui' => true,
            'Non' => false
        ];
        $builder
            ->add('cad_provenance', null, [
                'label' => 'Provenance',
            ])
            ->add('cad_divers', null, [
                'label' => 'Divers',
            ])
            ->add('cad_proprietaire_nom', null, [
                'label' => 'Nom propriétaire',
            ])
            ->add('cad_proprietaire_adresse', null, [
                'label' => 'Adresse propriétaire',
            ])
            ->add('cad_bon_etat', ChoiceType::class, [
                'label' => 'Bon état',
                'choices' => $etat,
                'data' => false,
            ])
            ->add('cad_sec_pers', ChoiceType::class, [
                'label' => 'Protection personne',
                'choices' => $personne,
                'data' => false,
            ])
            ->add('cad_sec_march', ChoiceType::class, [
                'label' => 'Protection marchandise',
                'choices' => $marchandise,
                'data' => false,
            ])
            ->add('cad_protec_env', ChoiceType::class, [
                'label' => 'Protection environnemtale',
                'choices' => $environnement,
                'data' => false,
            ])
            ->add('cad_numero', null, [
                'label' => 'Numéro',
            ])
            ->add('cad_immatriculation', null, [
                'label' => 'Immatriculation',
            ])
            ->add('cad_date_embarquement', null, [
                'label' => 'Data d\'embarquement',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('cad_lieu_embarquement', null, [
                'label' => 'Lieu d\'embarquement',
            ])
            ->add('cad_created', null, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
            ->add('cad_observation', null, [
                'label' => 'Observation',
            ])
            ->add('cad_conforme', ChoiceType::class, [
                'label' => 'Est-conforme',
                'choices' => $conforme,
                'data' => false,
            ])
            ->add('cad_is_active', ChoiceType::class, [
                'label' => 'Est-active',
                'choices' => $active,
                'data' => false,
            ])
            ->add('cad_genere', null, [
                'label' => 'Nombre constatation généré',
                'data' => 0,
            ])
            ->add('ct_centre_id', null, [
                'label' => 'Centre',
            ])
            ->add('ct_user_id', null, [
                'label' => 'Utilisateur',
            ])
            ->add('ct_verificateur_id', null, [
                'label' => 'Vérificateur',
            ])
            ->add('ct_const_av_ded_carac', EntityType::class, [
                'label' => 'Caractéristique constatation',
                'class' => CtConstAvDedCarac::class,
                'multiple'     => true,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtConstAvDed::class,
        ]);
    }
}
