<?php

namespace App\Form;

use App\Entity\CtConstAvDed;
use App\Entity\CtUser;
use App\Repository\CtUserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CtConstatationDisableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //$this->centre = $options["centre"];
        $disable = $options["disable"];
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
            ->add('ct_verificateur_id', EntityType::class, [
                'label' => 'Vérificateur',
                'class' => CtUser::class,
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
                'disabled' => $disable,
            ])
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
                'disabled' => $disable,
            ])
            ->add('cad_immatriculation', TextType::class, [
                'label' => 'Immatriculation',
                'disabled' => $disable,
            ])
            ->add('cad_provenance', TextType::class, [
                'label' => 'Provenance',
                'disabled' => $disable,
            ])
            ->add('cad_date_embarquement', DateType::class, [
                'label' => 'Data d\'embarquement',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                //'data' => new \DateTime('now'),
                'disabled' => $disable,
            ])
            ->add('cad_lieu_embarquement', TextType::class, [
                'label' => 'Lieu d\'embarquement',
                'disabled' => $disable,
            ])
            ->add('cad_proprietaire_nom', TextType::class, [
                'label' => 'Propriétaire',
                'disabled' => $disable,
            ])
            ->add('cad_proprietaire_adresse', TextType::class, [
                'label' => 'Adresse',
                'disabled' => $disable,
            ])
            ->add('cad_divers', TextType::class, [
                'label' => 'Divers',
                'disabled' => $disable,
            ])
            ->add('cad_observation', TextType::class, [
                'label' => 'Observation',
                'disabled' => $disable,
            ])
            ->add('cad_conforme', ChoiceType::class, [
                'label' => 'Est-conforme',
                'choices' => $conforme,
                //'data' => false,
                'disabled' => $disable,
            ])
            ->add('cad_bon_etat', ChoiceType::class, [
                'label' => 'Bon état',
                'choices' => $etat,
                //'data' => false,
                'disabled' => $disable,
            ])
            ->add('cad_sec_pers', ChoiceType::class, [
                'label' => 'Sécurité des personnes',
                'choices' => $personne,
                //'data' => false,
                'disabled' => $disable,
            ])
            ->add('cad_sec_march', ChoiceType::class, [
                'label' => 'Sécurité des marchandises',
                'choices' => $marchandise,
                //'data' => false,
                'disabled' => $disable,
            ])
            ->add('cad_protec_env', ChoiceType::class, [
                'label' => 'Protection de l\'environnement',
                'choices' => $environnement,
                //'data' => false,
                'disabled' => $disable,
            ])
            /* ->add('cad_numero')
            ->add('cad_created')
            ->add('cad_is_active')
            ->add('cad_genere')
            ->add('ct_centre_id')
            ->add('ct_const_av_ded_carac')
            ->add('ct_user_id') */
            ->add('ct_const_av_ded_carac_note_descriptive', CtConstatationCaracType::class, [
                'label' => 'Note descriptive',
                'mapped' => false,
                'disabled' => $disable,
            ])
            ->add('ct_const_av_ded_carac_carte_grise', CtConstatationCaracType::class, [
                'label' => 'Carte grise',
                'mapped' => false,
                'disabled' => $disable,
            ])
            ->add('ct_const_av_ded_carac_corps_vehicule', CtConstatationCaracType::class, [
                'label' => 'Corp du véhicule',
                'mapped' => false,
                'disabled' => $disable,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtConstAvDed::class,
            'disable' => false,
        ]);
        $resolver->setRequired([
            'disable',
        ]);
    }
}
