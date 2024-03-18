<?php

namespace App\Form;

use App\Repository\CtImprimeTechUseRepository;
use App\Entity\CtImprimeTechUse;
use App\Entity\CtImprimeTech;
use App\Repository\CtImprimeTechRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CtImprimeTechUseModulableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->centre = $options["centre"];
        $usage = $options["usage"];
        $controle = $options["controle"];
        $this->multiple = $options["multiple"];
        $this->carte = $options["carte"];
        $selection_multiple = false;
        if($this->multiple == true OR $this->carte == true){
            $selection_multiple = true;
        }
        $imprimer_technique = new CtImprimeTechRepository();
        $proces_verbal = $imprimer_technique->findBy(["ct_type_imprime_id" => 4]);
        $this->$liste_proces = new ArrayCollection();
        foreach($proces_verbal as $pv){
            $this->$liste_proces->add($pv->getId());
        }
        $builder
            ->add('ct_controle_id', null, [
                'label' => 'N° controle / N° enregistrement',
                'data' => $controle,
                'disabled' => true,
            ])
            ->add('imprime_technique_use_numero', EntityType::class, [
                'label' => 'N° de l\'imprimé technique',
                'class' => CtImprimeTechUse::class,
                'query_builder' => function(CtImprimeTechUseRepository $ctImprimeTechUseRepository){
                    if($this->carte == true){
                        $qb = $ctImprimeTechUseRepository->createQueryBuilder('u');
                        return $qb
                            ->Where('u.itu_used = :val1')
                            ->andWhere('u.ct_centre_id = :val2')
                            ->setParameter('val1', 0)
                            ->setParameter('val2', $this->centre)
                        ;
                    }elseif($this->multiple == true){
                        $qb = $ctImprimeTechUseRepository->createQueryBuilder('u');
                        return $qb
                            ->Where('u.itu_used = :val1')
                            ->andWhere('u.ct_centre_id = :val2')
                            //->andWhere('u.ct_imprime_tech_id = :val3 OR u.ct_imprime_tech_id = :val4 OR u.ct_imprime_tech_id = :val5 OR u.ct_imprime_tech_id = :val6')
                            ->andWhere('u.ct_imprime_tech_id IN :val3')
                            ->setParameter('val1', 0)
                            ->setParameter('val2', $this->centre)
                            ->setParameter('val3', $this->$liste_proces)
                            //->setParameter('val3', 12)
                            //->setParameter('val4', 13)
                            //->setParameter('val5', 14)
                            //->setParameter('val6', 15)
                        ;
                    }else{
                        $qb = $ctImprimeTechUseRepository->createQueryBuilder('u');
                        return $qb
                            ->Where('u.itu_used = :val1')
                            ->andWhere('u.ct_centre_id = :val2')
                            ->andWhere('u.ct_centre_id = :val2')
                            //->andWhere('u.ct_imprime_tech_id = :val3 OR u.ct_imprime_tech_id = :val4 OR u.ct_imprime_tech_id = :val5 OR u.ct_imprime_tech_id = :val6')
                            ->andWhere('u.ct_imprime_tech_id IN :val3')
                            ->setParameter('val1', 0)
                            ->setParameter('val2', $this->centre)
                            ->setParameter('val3', $this->$liste_proces)
                            //->setParameter('val3', 12)
                            //->setParameter('val4', 13)
                            //->setParameter('val5', 14)
                            //->setParameter('val6', 15)
                        ;
                    }
                },
                'multiple' => $selection_multiple,
                'attr' => [
                    'class' => 'multi select',
                    'multiple' => $selection_multiple,
                    'data-live-search' => true,
                    'data-select' => true,
                ],
                'mapped' => false,
                'disabled' => false,
            ])
            ->add('ct_usage_it_id', null, [
                'label' => 'Motif d\'utilisation IT',
                'data' => $usage,
                'disabled' => true,
            ])
            ->add('ct_utilisation_id', null, [
                'label' => 'Utilisation',
                'disabled' => false,
            ])
            /* ->add('ct_controle_id')
            ->add('itu_numero')
            ->add('itu_used')
            ->add('actived_at')
            ->add('created_at')
            ->add('updated_at')
            ->add('itu_observation')
            ->add('itu_is_visible')
            ->add('ct_bordereau_id')
            ->add('ct_centre_id')
            ->add('ct_imprime_tech_id')
            ->add('ct_user_id')
            ->add('ct_usage_it_id') */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CtImprimeTechUse::class,
            'mutiple' => false,
            'carte' => false,
        ]);
        $resolver->setRequired([
            'centre',
            'usage',
            'controle',
            'mutiple',
            'carte',
        ]);
    }
}
