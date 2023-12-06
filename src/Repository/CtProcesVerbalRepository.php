<?php

namespace App\Repository;

use App\Entity\CtProcesVerbal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtProcesVerbal>
 *
 * @method CtProcesVerbal|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtProcesVerbal|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtProcesVerbal[]    findAll()
 * @method CtProcesVerbal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtProcesVerbalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtProcesVerbal::class);
    }

    public function add(CtProcesVerbal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtProcesVerbal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtProcesVerbal[] Returns an array of CtProcesVerbal objects
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

//    public function findOneBySomeField($value): ?CtProcesVerbal
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
