<?php

namespace App\Repository;

use App\Entity\CtZoneDesserte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtZoneDesserte>
 *
 * @method CtZoneDesserte|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtZoneDesserte|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtZoneDesserte[]    findAll()
 * @method CtZoneDesserte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtZoneDesserteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtZoneDesserte::class);
    }

    public function add(CtZoneDesserte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtZoneDesserte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtZoneDesserte[] Returns an array of CtZoneDesserte objects
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

//    public function findOneBySomeField($value): ?CtZoneDesserte
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
