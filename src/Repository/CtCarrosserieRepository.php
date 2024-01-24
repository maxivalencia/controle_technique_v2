<?php

namespace App\Repository;

use App\Entity\CtCarrosserie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtCarrosserie>
 *
 * @method CtCarrosserie|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtCarrosserie|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtCarrosserie[]    findAll()
 * @method CtCarrosserie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtCarrosserieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtCarrosserie::class);
    }

    public function add(CtCarrosserie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtCarrosserie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtCarrosserie[] Returns an array of CtCarrosserie objects
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

//    public function findOneBySomeField($value): ?CtCarrosserie
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
