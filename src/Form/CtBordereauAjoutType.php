<?php

namespace App\Form;

use App\Repository\CtImprimeTechRepository;
use App\Entity\CtImprimeTech;
use App\Repository\CtCentreRepository;
use App\Entity\CtCentre;
use App\Entity\CtBordereau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtBordereauAjoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('bl_numero', null, [
            'label' => 'Numéro du bordereau',
        ])
        ->add('bl_debut_numero', null, [
            'label' => 'Début numéro imprimé technique',
            'data' => 0,
        ])
        ->add('bl_fin_numero', null, [
            'label' => 'Fin numéro imprimé technique',
            'data' => 0,
        ])
        /* ->add('bl_created_at', null, [
            'label' => 'Date création',
            'widget' => 'single_text',
            'attr' => [
                'class' => 'datetimepicker',
            ],
            'data' => new \DateTime('now'),
        ]) */
        /* ->add('bl_updated_at', null, [
            'label' => 'Date modification',
            'widget' => 'single_text',
            'attr' => [
                'class' => 'datetimepicker',
            ],
            'data' => new \DateTime('now'),
        ]) */
        ->add('ref_expr', null, [
            'label' => 'Référence expression de besoin',
        ])
        ->add('date_ref_expr', null, [
            'label' => 'Date reférence expression de besoin',
            'widget' => 'single_text',
            'attr' => [
                'class' => 'datetimepicker',
            ],
            'data' => new \DateTime('now'),
        ])
        /* ->add('bl_observation', null, [
            'label' => 'Observation',
        ]) */
        ->add('ct_centre_id', null, [
            'label' => 'Centre destinataire',
            'class' => CtCentre::class,
            'multiple' => false,
            'attr' => [
                'class' => 'multi select',
                'multiple' => false,
                'data-live-search' => true,
                'data-select' => true,
            ],
            'query_builder' => function(CtCentreRepository $ctCentreRepository){
                $qb = $ctCentreRepository->createQueryBuilder('u');
                return $qb
                    //->Where('u.id IN :val1')
                    ->orWhere('u.id = 3')
                    ->orWhere('u.id = 4')
                    ->orWhere('u.id = 6')
                    ->orWhere('u.id = 7')
                    ->orWhere('u.id = 9')
                    ->orWhere('u.id = 10')
                    ->orWhere('u.id = 11')
                    ->orWhere('u.id = 12')
                    ->orWhere('u.id = 13')
                    ->orWhere('u.id = 15')
                    ->orWhere('u.id = 16')
                    ->orWhere('u.id = 17')
                    ->orWhere('u.id = 18')
                    ->orWhere('u.id = 19')
                    ->orWhere('u.id = 20')
                    ->orWhere('u.id = 21')
                    ->orWhere('u.id = 22')
                    ->orWhere('u.id = 23')
                    ->orWhere('u.id = 24')
                    ->orWhere('u.id = 26')
                    ->orWhere('u.id = 27')
                    ->orWhere('u.id = 28')
                    ->orWhere('u.id = 29')
                    ->orWhere('u.id = 42')
                    ->orWhere('u.id = 88')
                    //->setParameter('val1', '(3,4,6,7,9,10,11,12,13,15,16,17,18,19,20,21,22,23,24,26,27,28,29,42,88)')
                    //->setParameter('val1', '42')
                    ->orderBy('u.id', 'ASC')
                    //->setMaxResults(11)
                ;
            },
        ])
        ->add('ct_imprime_tech_id', null, [
            'label' => 'Imprimé technique',
        ])
        /* ->add('ct_user_id', null, [
            'label' => 'Utilisateur',
        ]) */
        /*     ->add('bl_numero')
            ->add('bl_debut_numero')
            ->add('bl_fin_numero')
            ->add('bl_created_at')
            ->add('bl_updated_at')
            ->add('ref_expr')
            ->add('date_ref_expr')
            ->add('bl_observation')
            ->add('ct_centre_id')
            ->add('ct_imprime_tech_id')
            ->add('ct_user_id') */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtBordereau::class,
        ]);
    }
}
