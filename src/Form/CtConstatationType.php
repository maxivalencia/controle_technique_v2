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


class CtConstatationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->centre = $options["centre"];
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
                'query_builder' => function(CtUserRepository $ctUserRepository){
                    $qb = $ctUserRepository->createQueryBuilder('u');
                    return $qb
                        ->Where('u.ct_role_id = :val1')
                        ->andWhere('u.ct_centre_id = :val2')
                        ->setParameter('val1', 3)
                        ->setParameter('val2', $this->centre)
                    ;
                },
                'multiple' => false,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => false,
                    'style' => 'width:100%;',
                    'data-live-search' => true,
                    'data-select' => true,
                ],
            ])
            ->add('cad_immatriculation', TextType::class, [
                'label' => 'Immatriculation',
            ])
            ->add('cad_provenance', TextType::class, [
                'label' => 'Provenance',
            ])
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
            ])
            ->add('cad_date_embarquement', DateType::class, [
                'label' => 'Data d\'embarquement',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                //'data' => new \DateTime('now'),
            ])
            ->add('cad_lieu_embarquement', TextType::class, [
                'label' => 'Lieu d\'embarquement',
            ])
            ->add('cad_proprietaire_nom', TextType::class, [
                'label' => 'Propriétaire',
            ])
            ->add('cad_proprietaire_adresse', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('cad_divers', TextType::class, [
                'label' => 'Divers',
            ])
            ->add('cad_observation', TextType::class, [
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
            ->add('ct_const_av_ded_carac_note_descriptive', CtConstatationCaracType::class, [
                'label' => 'Note descriptive',
                'mapped' => false,
            ])
            ->add('ct_const_av_ded_carac_carte_grise', CtConstatationCaracType::class, [
                'label' => 'Carte grise',
                'mapped' => false,
            ])
            ->add('ct_const_av_ded_carac_corps_vehicule', CtConstatationCaracType::class, [
                'label' => 'Corp du véhicule',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtConstAvDed::class,
        ]);
        $resolver->setRequired([
            'centre',
        ]);
    }
}
