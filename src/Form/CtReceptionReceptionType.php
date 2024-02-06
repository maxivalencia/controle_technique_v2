<?php

namespace App\Form;

use App\Entity\CtReception;
use App\Entity\CtUtilisation;
use App\Entity\CtMotif;
use App\Entity\CtUser;
use App\Entity\CtCarrosserie;
use App\Entity\CtSourceEnergie;
use App\Repository\CtUserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CtReceptionReceptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->centre = $options["centre"];
        $builder
            ->add('ct_utilisation_id', EntityType::class, [
                'label' => 'Utilisation',
                'class' => CtUtilisation::class,
            ])
            ->add('ct_motif_id', EntityType::class, [
                'label' => 'Motif',
                'class' => CtMotif::class,
            ])
            ->add('rcp_immatriculation', TextType::class, [
                'label' => 'Immatriculation',
            ])
            ->add('rcp_proprietaire', TextType::class, [
                'label' => 'Propriétaire',
            ])
            ->add('rcp_profession', TextType::class, [
                'label' => 'Profession',
            ])
            ->add('rcp_adresse', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('rcp_nbr_assis', TextType::class, [
                'label' => 'Nombre de place assise',
                'data' => 0,
            ])
            ->add('rcp_ngr_debout', TextType::class, [
                'label' => 'Nombre de place debout',
                'data' => 0,
            ])
            ->add('ct_source_energie_id', EntityType::class, [
                'label' => 'Source d\'energie',
                'class' => CtSourceEnergie::class,
            ])
            ->add('ct_carrosserie_id', EntityType::class, [
                'label' => 'Carrosserie',
                'class' => CtCarrosserie::class,
            ])
            ->add('rcp_mise_service', DateType::class, [
                'label' => 'Date de mise en service',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
            ])
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
                }
            ])
            ->add('ct_vehicule_id', CtReceptionVehiculeType::class, [
                'label' => 'Véhicule',
                'required' => true,
            ])
            /* ->add('ct_type_reception_id', null, [
                'label' => 'Type de réception',
            ]) */
            /* ->add('rcp_num_pv')
            ->add('rcp_num_group')
            ->add('rcp_created')
            ->add('rcp_is_active')
            ->add('rcp_genere')
            ->add('rcp_observation')
            ->add('ct_centre_id')
            ->add('ct_user_id')
            ->add('ct_genre_id') */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtReception::class,
        ]);
        $resolver->setRequired([
            'centre',
        ]);
    }
}
