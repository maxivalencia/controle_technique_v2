<?php

namespace App\Repository;

use App\Entity\CtCarteGrise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtCarteGrise>
 *
 * @method CtCarteGrise|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtCarteGrise|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtCarteGrise[]    findAll()
 * @method CtCarteGrise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtCarteGriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtCarteGrise::class);
    }

    public function add(CtCarteGrise $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtCarteGrise $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtCarteGrise[] Returns an array of CtCarteGrise objects
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

//    public function findOneBySomeField($value): ?CtCarteGrise
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
