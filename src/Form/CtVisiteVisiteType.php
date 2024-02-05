<?php

namespace App\Form;

use App\Entity\CtAnomalie;
use App\Entity\CtCarteGrise;
use App\Entity\CtVisite;
use App\Entity\CtVisiteExtra;
use App\Entity\CtUser;
use App\Form\CtUserType;
use App\Entity\CtRole;
use App\Form\CtRoleType;
use App\Repository\CtRoleRepository;
use App\Repository\CtUserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CtVisiteVisiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /* $user = $this->getUser(); */
        $immatriculation = $options["immatriculation"];
        $this->centre = $options["centre"];
        $builder
            ->add('ct_centre_id', null, [
                'label' => 'Centre',
            ])
            ->add('ct_type_visite_id', null, [
                'label' => 'Type de visite',
            ])
            ->add('ct_usage_id', null, [
                'label' => 'Usage',
            ])
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
            ])
            ->add('vst_anomalie_id', EntityType::class, [
                'label' => 'Anomalies',
                'class' => CtAnomalie::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi is_anomalie',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
                'required' => false,
            ])
            ->add('vst_date_expiration', null, [
                'label' => 'Date d\'expiration',
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
                    $centre = $this->centre;
                    return $qb
                        ->Where('u.ct_role_id = :val1 OR u.ct_role_id = :val2')
                        ->andWhere('u.ct_centre_id = :val3')
                        ->setParameter('val1', 3)
                        ->setParameter('val2', 22)
                        ->setParameter('val3', $centre)
                    ;
                }
            ])
            ->add('vst_extra', EntityType::class, [
                'label' => 'Extra',
                'class' => CtVisiteExtra::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
                'required' => false,
            ])
            ->add('ct_carte_grise_id', CtVisiteCarteGriseType::class, [
                //'class' => CtCarteGrise::class,
                'label' => 'Carte Grise',
                'disabled' => true,
            ])
            ->add('vst_duree_reparation', null, [
                'label' => 'Durée de reparation accordée',
            ])
            ->add('vst_observation', HiddenType::class, [
                'label' => 'immatriculation',
                'data' => $immatriculation,
                //'disabled' => true,
            ])

            /* ->add('vst_num_pv')
            ->add('vst_num_feuille_caisse')
            ->add('vst_created')
            ->add('vst_updated')
            ->add('vst_is_apte')
            ->add('vst_is_contre_visite')
            ->add('vst_is_active')
            ->add('vst_genere')
            ->add('vst_observation')
            ->add('ct_user_id') */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtVisite::class,
        ]);
        $resolver->setRequired([
            'immatriculation',
            'centre',
        ]);
    }
}
