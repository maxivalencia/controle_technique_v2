<?php

namespace App\Repository;

use App\Entity\CtUsageTarif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtUsageTarif>
 *
 * @method CtUsageTarif|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtUsageTarif|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtUsageTarif[]    findAll()
 * @method CtUsageTarif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtUsageTarifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtUsageTarif::class);
    }

    public function add(CtUsageTarif $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtUsageTarif $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtUsageTarif[] Returns an array of CtUsageTarif objects
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

//    public function findOneBySomeField($value): ?CtUsageTarif
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
