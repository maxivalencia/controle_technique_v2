<?php

namespace App\Form;

use App\Entity\CtConstAvDed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CtConstatationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
            ->add('ct_verificateur_id', null, [
                'label' => 'Vérificateur',
            ])
            ->add('cad_immatriculation', null, [
                'label' => 'Immatriculation',
            ])
            ->add('cad_provenance', null, [
                'label' => 'Provenance',
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
            ->add('cad_proprietaire_nom', null, [
                'label' => 'Propriétaire',
            ])
            ->add('cad_proprietaire_adresse', null, [
                'label' => 'Adresse',
            ])
            ->add('cad_divers', null, [
                'label' => 'Divers',
            ])
            ->add('cad_observation', null, [
                'label' => 'Observation',
            ])
            ->add('cad_conforme', ChoiceType::class, [
                'label' => 'Est-conforme',
                'choices' => $conforme,
                'data' => false,
            ])
            ->add('cad_bon_etat', ChoiceType::class, [
                'label' => 'Bon état',
                'choices' => $etat,
                'data' => false,
            ])
            ->add('cad_sec_pers', ChoiceType::class, [
                'label' => 'Sécurité des personnes',
                'choices' => $personne,
                'data' => false,
            ])
            ->add('cad_sec_march', ChoiceType::class, [
                'label' => 'Sécurité des marchandises',
                'choices' => $marchandise,
                'data' => false,
            ])
            ->add('cad_protec_env', ChoiceType::class, [
                'label' => 'Protection de l\'environnement',
                'choices' => $environnement,
                'data' => false,
            ])
            /* ->add('cad_numero')
            ->add('cad_created')
            ->add('cad_is_active')
            ->add('cad_genere')
            ->add('ct_centre_id')
            ->add('ct_const_av_ded_carac')
            ->add('ct_user_id') */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtConstAvDed::class,
        ]);
    }
}
