<?php

namespace App\Repository;

use App\Entity\CtReception;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtReception>
 *
 * @method CtReception|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtReception|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtReception[]    findAll()
 * @method CtReception[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtReceptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtReception::class);
    }

    public function add(CtReception $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtReception $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtReception[] Returns an array of CtReception objects
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

//    public function findOneBySomeField($value): ?CtReception
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * @return CtHistoriqueType[] Returns an array of CtHistoriqueType objects
     */
    public function findByFicheDeControle($type, $centre, $date): array
    {
        //$date_request = new \DateTime($date->format('Y-m-d'));
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_type_reception_id = :val1')
            ->andWhere('c.ct_centre_id = :val2')
            ->andWhere('c.rcp_created LIKE :val3')
            ->andWhere('c.rcp_is_active = 1')
            ->setParameter('val1', $type)
            ->setParameter('val2', $centre)
            ->setParameter('val3', '%'.$date->format('Y-m-d').'%')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
