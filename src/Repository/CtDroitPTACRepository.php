<?php

namespace App\Repository;

use App\Entity\CtDroitPTAC;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtDroitPTAC>
 *
 * @method CtDroitPTAC|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtDroitPTAC|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtDroitPTAC[]    findAll()
 * @method CtDroitPTAC[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtDroitPTACRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtDroitPTAC::class);
    }

    public function add(CtDroitPTAC $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtDroitPTAC $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtDroitPTAC[] Returns an array of CtDroitPTAC objects
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

//    public function findOneBySomeField($value): ?CtDroitPTAC
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * @return CtDroitPTAC[] Returns an array of CtDroitPTAC objects
     */
    public function findDroitValide($genreCategorie, $droitPtac, $ptac): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_genre_categorie_id = :val1')
            ->andWhere('c.ct_type_droit_ptac_id = :val2')
            ->andWhere('c.dp_prix_min <= :val3 AND c.dp_prix_max > :val3')
            ->setParameter('val1', $genreCategorie)
            ->setParameter('val2', $droitPtac)
            ->setParameter('val3', $ptac)
            ->orderBy('c.ct_arrete_prix_id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
}
