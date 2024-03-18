<?php

namespace App\Form;

use App\Entity\CtAutreVente;
use App\Entity\CtUser;
use App\Repository\CtUserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CtAutreVenteAutreVenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->centre = $options["centre"];
        $builder
            /* ->add('auv_is_visible') */
            /* ->add('auv_created_at') */
            ->add('controle_id', null, [
                'label' => 'Numéro de controle Visite \\ Reception \\ Constatation',
            ])
            ->add('ct_usage_it', null, [
                'label' => 'Type autre service',
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
                'mapped' => false,
            ])
            /* ->add('ct_carte_grise_id', null, [
                'label' => 'Numéro d\'immatriculation',
            ]) */
            /* ->add('ct_centre_id') */
            ->add('auv_extra', null, [
                'label' => 'Imprimé technique utiliser',
            ])
            ->add('auv_validite', null, [
                'label' => 'Validité',
                'empty_data' => '',
            ])
            ->add('auv_itineraire', null, [
                'label' => 'Itinéraire',
                'empty_data' => '',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtAutreVente::class,
        ]);
        $resolver->setRequired([
            'centre',
        ]);
    }
}
