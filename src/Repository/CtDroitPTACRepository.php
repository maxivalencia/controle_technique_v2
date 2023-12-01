<?php

namespace App\Repository;

use App\Entity\CtDroitPTAC;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtDroitPTAC>
 *
 * @method CtDroitPTAC|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtDroitPTAC|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtDroitPTAC[]    findAll()
 * @method CtDroitPTAC[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtDroitPTACRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtDroitPTAC::class);
    }

    public function add(CtDroitPTAC $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtDroitPTAC $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtDroitPTAC[] Returns an array of CtDroitPTAC objects
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

//    public function findOneBySomeField($value): ?CtDroitPTAC
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
