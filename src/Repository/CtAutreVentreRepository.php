<?php

namespace App\Repository;

use App\Entity\CtAutreVentre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtAutreVentre>
 *
 * @method CtAutreVentre|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtAutreVentre|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtAutreVentre[]    findAll()
 * @method CtAutreVentre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtAutreVentreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtAutreVentre::class);
    }

//    /**
//     * @return CtAutreVentre[] Returns an array of CtAutreVentre objects
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

//    public function findOneBySomeField($value): ?CtAutreVentre
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
