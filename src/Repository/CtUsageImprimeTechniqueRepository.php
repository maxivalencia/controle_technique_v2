<?php

namespace App\Repository;

use App\Entity\CtUsageImprimeTechnique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtUsageImprimeTechnique>
 *
 * @method CtUsageImprimeTechnique|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtUsageImprimeTechnique|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtUsageImprimeTechnique[]    findAll()
 * @method CtUsageImprimeTechnique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtUsageImprimeTechniqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtUsageImprimeTechnique::class);
    }

    public function add(CtUsageImprimeTechnique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtUsageImprimeTechnique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtUsageImprimeTechnique[] Returns an array of CtUsageImprimeTechnique objects
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

//    public function findOneBySomeField($value): ?CtUsageImprimeTechnique
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
