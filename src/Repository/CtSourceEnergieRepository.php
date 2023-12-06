<?php

namespace App\Repository;

use App\Entity\CtSourceEnergie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtSourceEnergie>
 *
 * @method CtSourceEnergie|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtSourceEnergie|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtSourceEnergie[]    findAll()
 * @method CtSourceEnergie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtSourceEnergieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtSourceEnergie::class);
    }

    public function add(CtSourceEnergie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtSourceEnergie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtSourceEnergie[] Returns an array of CtSourceEnergie objects
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

//    public function findOneBySomeField($value): ?CtSourceEnergie
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
