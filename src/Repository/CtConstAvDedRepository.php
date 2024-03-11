<?php

namespace App\Repository;

use App\Entity\CtConstAvDed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtConstAvDed>
 *
 * @method CtConstAvDed|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtConstAvDed|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtConstAvDed[]    findAll()
 * @method CtConstAvDed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtConstAvDedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtConstAvDed::class);
    }

    public function add(CtConstAvDed $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtConstAvDed $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtConstAvDed[] Returns an array of CtConstAvDed objects
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

//    public function findOneBySomeField($value): ?CtConstAvDed
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * @return CtConstAvDed[] Returns an array of CtConstAvDed objects
     */
    public function findByFicheDeControle($centre, $date): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_centre_id = :val1')
            ->andWhere('c.cad_created LIKE :val2')
            ->andWhere('c.rcp_is_active = :val3')
            ->setParameter('val1', $centre)
            ->setParameter('val2', '%'.$date->format('Y-m-d').'%')
            ->setParameter('val3', 1)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

}
