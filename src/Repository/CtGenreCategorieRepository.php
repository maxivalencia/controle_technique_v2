<?php

namespace App\Repository;

use App\Entity\CtGenreCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CtGenreCategorie>
 *
 * @method CtGenreCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtGenreCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtGenreCategorie[]    findAll()
 * @method CtGenreCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtGenreCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtGenreCategorie::class);
    }

    public function add(CtGenreCategorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtGenreCategorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CtGenreCategorie[] Returns an array of CtGenreCategorie objects
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

//    public function findOneBySomeField($value): ?CtGenreCategorie
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
