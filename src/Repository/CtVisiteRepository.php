<?php

namespace App\Repository;

use App\Entity\CtVisite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtVisite>
 *
 * @method CtVisite|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtVisite|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtVisite[]    findAll()
 * @method CtVisite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtVisiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtVisite::class);
    }

    public function add(CtVisite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtVisite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtVisite[] Returns an array of CtVisite objects
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

//    public function findOneBySomeField($value): ?CtVisite
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * @return CtVisite[] Returns an array of CtVisite objects
     */
    public function findByFicheDeControle($value1, $value2, $value3): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_type_visite_id = :val1')
            ->andWhere('c.ct_centre_id = :val2')
            ->andWhere('c.vst_created LIKE :val3')
            ->andWhere('c.vst_is_active = 1')
            ->setParameter('val1', $value1)
            ->setParameter('val2', $value2)
            ->setParameter('val3', '%'.$value3->format('Y-m-d').'%')
            ->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}
