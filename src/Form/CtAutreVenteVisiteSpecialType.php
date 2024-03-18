<?php

namespace App\Form;

use App\Entity\CtAutreVente;
use App\Entity\CtUser;
use App\Entity\CtUsageImprimerTechnique;
use App\Repository\CtUserRepository;
use App\Repository\CtUsageImprimerTechniqueRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CtAutreVenteVisiteSpecialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->centre = $options["centre"];
        $controle = $options["controle"];
        $usage = $options["usage"];
        $immatriculation = $options["immatriculation"];
        $builder
            /* ->add('auv_is_visible') */
            /* ->add('auv_created_at') */
            ->add('controle_id', null, [
                'label' => 'Numéro de controle technique du vehicule',
                'data' => $controle,
                'disabled' => true,
            ])
            ->add('ct_usage_it', null, [
                'label' => 'Type autre service',
                'data' => $usage,
                'disabled' => true,
            ])
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
            ])
            /* ->add('ct_autre_tarif_id') */
            /* ->add('user_id') */
            ->add('verificateur_id', EntityType::class, [
                'label' => 'Vérificateur responsable',
                'class' => CtUser::class,
                'query_builder' => function(CtUserRepository $ctUserRepository){
                    $qb = $ctUserRepository->createQueryBuilder('u');
                    $centre = $this->centre;
                    return $qb
                        ->Where('u.ct_role_id = :val1')
                        ->andWhere('u.ct_centre_id = :val2')
                        ->setParameter('val1', 3)
                        ->setParameter('val2', $centre)
                    ;
                }
            ])
            ->add('ct_carte_grise', null, [
                'label' => 'Numéro d\'immatriculation',
                'data' => $immatriculation,
                'disabled' => true,
                'mapped' => false,
            ])
            /* ->add('ct_carte_grise_id', null, [
                'label' => 'Numéro d\'immatriculation',
            ]) */
            /* ->add('ct_centre_id') */
            /* ->add('auv_extra', null, [
                'label' => 'Imprimé technique utiliser',
            ]) */
            ->add('auv_validite', null, [
                'label' => 'Validité',
                'empty_data' => '',
            ])
            ->add('auv_itineraire', null, [
                'label' => 'Itinéraire',
                'empty_data' => '',
            ])
            /* ->add('auv_is_visible')
            ->add('auv_created_at')
            ->add('controle_id')
            ->add('auv_validite')
            ->add('auv_itineraire')
            ->add('auv_num_pv')
            ->add('ct_usage_it')
            ->add('ct_autre_tarif_id')
            ->add('user_id')
            ->add('verificateur_id')
            ->add('ct_carte_grise_id')
            ->add('ct_centre_id')
            ->add('auv_extra') */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtAutreVente::class,
            'usage' => CtUsageImprimerTechnique::class,
        ]);
        $resolver->setRequired([
            'centre',
            'controle',
            'usage',
            'immatriculation',
        ]);
    }
}
