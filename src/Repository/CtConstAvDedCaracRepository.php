<?php

namespace App\Repository;

use App\Entity\CtConstAvDedCarac;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtConstAvDedCarac>
 *
 * @method CtConstAvDedCarac|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtConstAvDedCarac|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtConstAvDedCarac[]    findAll()
 * @method CtConstAvDedCarac[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtConstAvDedCaracRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtConstAvDedCarac::class);
    }

    public function add(CtConstAvDedCarac $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtConstAvDedCarac $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtConstAvDedCarac[] Returns an array of CtConstAvDedCarac objects
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

//    public function findOneBySomeField($value): ?CtConstAvDedCarac
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
