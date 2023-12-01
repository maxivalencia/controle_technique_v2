<?php

namespace App\Repository;

use App\Entity\CtHistoriqueType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtHistoriqueType>
 *
 * @method CtHistoriqueType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtHistoriqueType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtHistoriqueType[]    findAll()
 * @method CtHistoriqueType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtHistoriqueTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtHistoriqueType::class);
    }

    public function add(CtHistoriqueType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtHistoriqueType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtHistoriqueType[] Returns an array of CtHistoriqueType objects
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

//    public function findOneBySomeField($value): ?CtHistoriqueType
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
