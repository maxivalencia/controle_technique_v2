<?php

namespace App\Form;

use App\Entity\CtAnomalie;
use App\Entity\CtCarteGrise;
use App\Entity\CtVisite;
use App\Entity\CtVisiteExtra;
use App\Entity\CtImprimeTech;
use App\Entity\CtUser;
use App\Form\CtUserType;
use App\Entity\CtRole;
use App\Form\CtRoleType;
use App\Repository\CtRoleRepository;
use App\Repository\CtUserRepository;
use App\Repository\CtVisiteExtraRepository;
use App\Repository\CtImprimeTechRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CtVisiteVisiteDisableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $disable = $options["disable"];
        $immatriculation = $options["immatriculation"];
        $builder
            ->add('ct_centre_id', null, [
                'label' => 'Centre',
                'disabled' => $disable,
            ])
            ->add('ct_type_visite_id', null, [
                'label' => 'Type de visite',
                //'required' => true,
                'disabled' => $disable,
            ])
            ->add('ct_usage_id', null, [
                'label' => 'Usage',
                //'required' => true,
                'disabled' => $disable,
            ])
            /* ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
                //'required' => true,
                'disabled' => $disable,
            ]) */
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
                'disabled' => $disable,
            ])
            ->add('vst_date_expiration', null, [
                'label' => 'Date d\'expiration',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'data' => new \DateTime('now'),
                'disabled' => $disable,
            ])
            ->add('ct_verificateur_id', EntityType::class, [
                'label' => 'Vérificateur',
                'class' => CtUser::class,
                'disabled' => $disable,
            ])
            /* ->add('vst_extra', EntityType::class, [
                'label' => 'Extra',
                'class' => CtVisiteExtra::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi select',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
                'required' => false,
            ]) */
            /* ->add('vst_extra', EntityType::class, [
                'label' => 'Extra',
                'class' => CtVisiteExtra::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi select',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
                'query_builder' => function(CtVisiteExtraRepository $ctVisiteExtraRepository){
                    $qb = $ctVisiteExtraRepository->createQueryBuilder('u');
                    return $qb
                        ->orderBy('u.id', 'ASC')
                        ->setMaxResults(11)
                    ;
                },
                'required' => false,
                'disabled' => $disable,
            ]) */
            ->add('vst_imprime_tech_id', EntityType::class, [
                'label' => 'Extra',
                'class' => CtImprimeTech::class,
                'multiple' => true,
                'attr' => [
                    'class' => 'multi',
                    'multiple' => true,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
                'query_builder' => function(CtImprimeTechRepository $ctImprimeTechRepository){
                    $qb = $ctImprimeTechRepository->createQueryBuilder('u');
                    return $qb
                        ->andWhere('u.ct_type_imprime_id = :val1 OR u.ct_type_imprime_id = :val2')
                        ->setParameter('val1', 1)
                        ->setParameter('val2', 2)
                        ->orderBy('u.id', 'ASC')
                        //->setMaxResults(11)
                    ;
                },
                'required' => false,
            ])
            ->add('ct_carte_grise_id', CtVisiteCarteGriseType::class, [
                //'class' => CtCarteGrise::class,
                'label' => 'Carte Grise',
                'disabled' => $disable,
            ])
            ->add('vst_duree_reparation', null, [
                'label' => 'Durée de reparation accordée',
                'disabled' => $disable,
            ])
            ->add('vst_observation', HiddenType::class, [
                'label' => 'immatriculation',
                'data' => $immatriculation,
                'disabled' => $disable,
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
            'disable' => false,
        ]);
        $resolver->setRequired([
            'disable',
            'immatriculation',
        ]);
    }
}
