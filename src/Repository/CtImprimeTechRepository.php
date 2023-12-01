<?php

namespace App\Repository;

use App\Entity\CtImprimeTech;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtImprimeTech>
 *
 * @method CtImprimeTech|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtImprimeTech|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtImprimeTech[]    findAll()
 * @method CtImprimeTech[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtImprimeTechRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtImprimeTech::class);
    }

    public function add(CtImprimeTech $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtImprimeTech $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtImprimeTech[] Returns an array of CtImprimeTech objects
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

//    public function findOneBySomeField($value): ?CtImprimeTech
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
