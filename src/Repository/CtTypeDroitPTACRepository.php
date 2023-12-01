<?php

namespace App\Repository;

use App\Entity\CtTypeDroitPTAC;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtTypeDroitPTAC>
 *
 * @method CtTypeDroitPTAC|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtTypeDroitPTAC|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtTypeDroitPTAC[]    findAll()
 * @method CtTypeDroitPTAC[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtTypeDroitPTACRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtTypeDroitPTAC::class);
    }

    public function add(CtTypeDroitPTAC $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtTypeDroitPTAC $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtTypeDroitPTAC[] Returns an array of CtTypeDroitPTAC objects
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

//    public function findOneBySomeField($value): ?CtTypeDroitPTAC
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
