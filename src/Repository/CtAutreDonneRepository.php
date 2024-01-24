<?php

namespace App\Repository;

use App\Entity\CtAutreDonne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtAutreDonne>
 *
 * @method CtAutreDonne|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtAutreDonne|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtAutreDonne[]    findAll()
 * @method CtAutreDonne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtAutreDonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtAutreDonne::class);
    }

    public function add(CtAutreDonne $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtAutreDonne $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtAutreDonne[] Returns an array of CtAutreDonne objects
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

//    public function findOneBySomeField($value): ?CtAutreDonne
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
