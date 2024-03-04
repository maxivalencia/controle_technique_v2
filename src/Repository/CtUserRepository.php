<?php

namespace App\Repository;

use App\Entity\CtUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<CtUser>
 *
 * @method CtUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method CtUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method CtUser[]    findAll()
 * @method CtUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CtUserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CtUser::class);
    }

    public function add(CtUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CtUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof CtUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

//    /**
//     * @return CtUser[] Returns an array of CtUser objects
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

//    public function findOneBySomeField($value): ?CtUser
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * @return CtUser[] Returns an array of CtUser objects
     */
    public function findBySecretaire(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_role_id = :val1')
            ->orWhere('c.ct_role_id = :val2')
            ->orWhere('c.ct_role_id = :val3')
            ->orWhere('c.ct_role_id = :val4')
            ->orWhere('c.ct_role_id = :val5')
            ->orWhere('c.ct_role_id = :val6')
            ->orWhere('c.ct_role_id = :val7')
            ->setParameter('val1', 4)
            ->setParameter('val2', 5)
            ->setParameter('val3', 10)
            ->setParameter('val4', 11)
            ->setParameter('val5', 12)
            ->setParameter('val6', 13)
            ->setParameter('val7', 14)
            ->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return CtUser[] Returns an array of CtUser objects
     */
    public function findByRegisseur(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_role_id = :val1')
            ->orWhere('c.ct_role_id = :val2')
            ->orWhere('c.ct_role_id = :val3')
            ->orWhere('c.ct_role_id = :val4')
            ->orWhere('c.ct_role_id = :val5')
            ->orWhere('c.ct_role_id = :val6')
            ->orWhere('c.ct_role_id = :val7')
            ->orWhere('c.ct_role_id = :val8')
            ->setParameter('val1', 8)
            ->setParameter('val2', 15)
            ->setParameter('val3', 16)
            ->setParameter('val4', 17)
            ->setParameter('val5', 18)
            ->setParameter('val6', 19)
            ->setParameter('val7', 20)
            ->setParameter('val8', 21)
            ->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return CtUser[] Returns an array of CtUser objects
     */
    public function findByVerificateur(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_role_id = :val1')
            ->orWhere('c.ct_role_id = :val2')
            ->setParameter('val1', 3)
            ->setParameter('val2', 22)
            ->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return CtUser[] Returns an array of CtUser objects
     */
    public function findByChefDeCentre(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_role_id = :val1')
            ->setParameter('val1', 22)
            ->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return CtUser[] Returns an array of CtUser objects
     */
    public function findByVerificateurCentre(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ct_role_id = :val1 OR c.ct_role_id = :val2')
            //->andWhere('c.ct_centre_id = :val3')
            ->setParameter('val1', 3)
            ->setParameter('val2', 22)
            //->setParameter('val3', $user)
            ->orderBy('c.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}
