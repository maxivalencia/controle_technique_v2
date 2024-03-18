<?php

namespace App\Repository;

use App\Entity\CtImprimeTechUse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtImprimeTechUse>
 *
 * @method CtImprimeTechUse|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtImprimeTechUse|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtImprimeTechUse[]    findAll()
 * @method CtImprimeTechUse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtImprimeTechUseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtImprimeTechUse::class);
    }

    public function add(CtImprimeTechUse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtImprimeTechUse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtImprimeTechUse[] Returns an array of CtImprimeTechUse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CtImprimeTechUse
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * @return CtImprimeTechUse[] Returns an array of CtImprimeTechUse objects
     */
    public function findByUtilisation($centre, $date): array
    {
        return $this->createQueryBuilder('c')
            //->select('DISTINCT c.ct_controle_id')
            ->andWhere('c.ct_centre_id = :val1')
            ->andWhere('c.created_at LIKE :val2')
            ->andWhere('c.itu_is_visible = :val3')
            ->setParameter('val1', $centre)
            ->setParameter('val2', '%'.$date->format('Y-m-d').'%')
            ->setParameter('val3', 1)
            ->orderBy('c.itu_numero', 'ASC')
            ->groupBy('c.ct_controle_id')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return CtImprimeTechUse[] Returns an array of CtImprimeTechUse objects
     */
    public function findByUtilisationUnique($centre, $date): array
    {
        return $this->createQueryBuilder('c')
            ->select('DISTINCT c.ct_controle_id')
            ->andWhere('c.ct_centre_id = :val1')
            ->andWhere('c.created_at LIKE :val2')
            ->andWhere('c.itu_is_visible = :val3')
            ->setParameter('val1', $centre)
            ->setParameter('val2', '%'.$date->format('Y-m-d').'%')
            ->setParameter('val3', 1)
            ->orderBy('c.itu_numero', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return CtImprimeTechUse[] Returns an array of CtImprimeTechUse objects
     */
    public function findByUtilisationControle($centre, $date, $controleId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_centre_id = :val1')
            ->andWhere('c.created_at LIKE :val2')
            ->andWhere('c.ct_controle_id = :val3')
            ->andWhere('c.itu_is_visible = :val4')
            ->setParameter('val1', $centre)
            ->setParameter('val2', '%'.$date->format('Y-m-d').'%')
            ->setParameter('val3', $controleId)
            ->setParameter('val4', 1)
            ->orderBy('c.itu_numero', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByUtilisationControle($centre, $date, $controleId): ?CtImprimeTechUse
    {
        return $this->createQueryBuilder('c')
            ->select('DISTINCT c')
            ->andWhere('c.ct_centre_id = :val1')
            ->andWhere('c.created_at LIKE :val2')
            ->andWhere('c.ct_controle_id = :val3')
            ->andWhere('c.itu_is_visible = :val4')
            ->setParameter('val1', $centre)
            ->setParameter('val2', '%'.$date->format('Y-m-d').'%')
            ->setParameter('val3', $controleId)
            ->setParameter('val4', 1)
            ->orderBy('c.itu_numero', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            //->getResult()
            ->getOneOrNullResult()
            //->getSingleResult()
        ;
    }

    /**
     * @return CtImprimeTechUse[] Returns an array of CtImprimeTechUse objects
     */
    public function findExistant($centre, $mois, $annee, $id_imprime): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_centre_id = :val1')
            ->andWhere('c.actived_at < :val2')
            ->andWhere('c.itu_used = :val3')
            ->andWhere('c.ct_imprime_tech_id = :val4')
            ->andWhere('c.itu_is_visible = :val5')
            ->setParameter('val1', $centre)
            ->setParameter('val2', $annee.'-'.$mois.'-01')
            ->setParameter('val3', 0)
            ->setParameter('val4', $id_imprime)
            ->setParameter('val5', 1)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return CtImprimeTechUse[] Returns an array of CtImprimeTechUse objects
     */
    public function findRecu($centre, $mois, $annee, $id_imprime): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_centre_id = :val1')
            ->andWhere('c.actived_at LIKE :val2')
            ->andWhere('c.ct_imprime_tech_id = :val3')
            ->andWhere('c.itu_is_visible = :val4')
            ->setParameter('val1', $centre)
            ->setParameter('val2', '%'.$annee.'-'.$mois.'-31%')
            ->setParameter('val3', $id_imprime)
            ->setParameter('val4', 1)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return CtImprimeTechUse[] Returns an array of CtImprimeTechUse objects
     */
    public function findUtiliser($centre, $mois, $annee, $id_imprime): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_centre_id = :val1')
            ->andWhere('c.created_at LIKE :val2')
            ->andWhere('c.itu_used = :val3')
            ->andWhere('c.ct_imprime_tech_id = :val4')
            ->andWhere('c.itu_is_visible = :val5')
            ->setParameter('val1', $centre)
            ->setParameter('val2', '%'.$annee.'-'.$mois.'%')
            ->setParameter('val3', 1)
            ->setParameter('val4', $id_imprime)
            ->setParameter('val5', 1)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findNombreExistant($centre, $mois, $annee, $id_imprime): ?int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->andWhere('c.ct_centre_id = :val1')
            ->andWhere('c.actived_at < :val2')
            ->andWhere('c.itu_used = :val3 OR c.created_at > :val2')
            ->andWhere('c.ct_imprime_tech_id = :val4')
            ->andWhere('c.itu_is_visible = :val5')
            ->setParameter('val1', $centre)
            ->setParameter('val2', $annee.'-'.$mois.'-01')
            ->setParameter('val3', 0)
            ->setParameter('val4', $id_imprime)
            ->setParameter('val5', 1)
            //->setParameter('val7', $annee.'-'.$mois.'-31')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
            //->getOneOrNullResult()
        ;
    }

    public function findNombreRecu($centre, $mois, $annee, $id_imprime): ?int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->andWhere('c.ct_centre_id = :val1')
            ->andWhere('c.actived_at LIKE :val2')
            ->andWhere('c.ct_imprime_tech_id = :val3')
            ->andWhere('c.itu_is_visible = :val4')
            ->setParameter('val1', $centre)
            ->setParameter('val2', '%'.$annee.'-'.$mois.'%')
            ->setParameter('val3', $id_imprime)
            ->setParameter('val4', 1)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
            //->getOneOrNullResult()
        ;
    }

    public function findNombreUtiliserParticulier($centre, $mois, $annee, $id_imprime): ?int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->andWhere('c.ct_centre_id = :val1')
            ->andWhere('c.created_at LIKE :val2')
            ->andWhere('c.itu_used = :val3')
            ->andWhere('c.ct_imprime_tech_id = :val4')
            ->andWhere('c.itu_is_visible = :val5')
            ->andWhere('c.ct_utilisation_id = :val6 OR c.ct_utilisation_id = :val7')
            ->setParameter('val1', $centre)
            ->setParameter('val2', '%'.$annee.'-'.$mois.'%')
            ->setParameter('val3', 1)
            ->setParameter('val4', $id_imprime)
            ->setParameter('val5', 1)
            ->setParameter('val6', 2)
            ->setParameter('val7', null)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
            //->getOneOrNullResult()
        ;
    }

    public function findNombreUtiliserAdministratif($centre, $mois, $annee, $id_imprime): ?int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c) ')
            ->andWhere('c.ct_centre_id = :val1')
            ->andWhere('c.created_at LIKE :val2')
            ->andWhere('c.itu_used = :val3')
            ->andWhere('c.ct_imprime_tech_id = :val4')
            ->andWhere('c.itu_is_visible = :val5')
            ->andWhere('c.ct_utilisation_id = :val6')
            ->setParameter('val1', $centre)
            ->setParameter('val2', '%'.$annee.'-'.$mois.'%')
            ->setParameter('val3', 1)
            ->setParameter('val4', $id_imprime)
            ->setParameter('val5', 1)
            ->setParameter('val6', 1)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
            //->getOneOrNullResult()
        ;
    }
}
