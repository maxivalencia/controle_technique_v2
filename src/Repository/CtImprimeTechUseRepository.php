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
}
