<?php

namespace App\Repository;

use App\Entity\CtConstAvDedType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtConstAvDedType>
 *
 * @method CtConstAvDedType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtConstAvDedType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtConstAvDedType[]    findAll()
 * @method CtConstAvDedType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtConstAvDedTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtConstAvDedType::class);
    }

    public function add(CtConstAvDedType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtConstAvDedType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtConstAvDedType[] Returns an array of CtConstAvDedType objects
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

//    public function findOneBySomeField($value): ?CtConstAvDedType
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
