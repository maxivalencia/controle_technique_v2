<?php

namespace App\Repository;

use App\Entity\CtTypeImprime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtTypeImprime>
 *
 * @method CtTypeImprime|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtTypeImprime|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtTypeImprime[]    findAll()
 * @method CtTypeImprime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtTypeImprimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtTypeImprime::class);
    }

//    /**
//     * @return CtTypeImprime[] Returns an array of CtTypeImprime objects
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

//    public function findOneBySomeField($value): ?CtTypeImprime
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
