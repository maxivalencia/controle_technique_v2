<?php

namespace App\Repository;

use App\Entity\CtBordereau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtBordereau>
 *
 * @method CtBordereau|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtBordereau|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtBordereau[]    findAll()
 * @method CtBordereau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtBordereauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtBordereau::class);
    }

    public function add(CtBordereau $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtBordereau $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtBordereau[] Returns an array of CtBordereau objects
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

//    public function findOneBySomeField($value): ?CtBordereau
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    /**
     * @return CtBordereau[] Returns an array of CtBordereau objects
     */
    public function findBordereauDoublon($imprime, $min, $max): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_imprime_tech_id = :val1')
            ->andWhere('c.bl_debut_numero BETWEEN :val2 and :val3 OR c.bl_fin_numero BETWEEN :val2 and :val3 OR :val2 BETWEEN c.bl_debut_numero and c.bl_fin_numero OR :val3 BETWEEN c.bl_debut_numero and c.bl_fin_numero')
            ->setParameter('val1', $imprime)
            ->setParameter('val2', $min)
            ->setParameter('val3', $max)
            ->getQuery()
            ->getResult()
        ;
    }
}
