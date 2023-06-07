<?php

namespace App\Repository;

use App\Entity\GardenUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GardenUser>
 *
 * @method GardenUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method GardenUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method GardenUser[]    findAll()
 * @method GardenUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GardenUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GardenUser::class);
    }

    public function save(GardenUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GardenUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /**
     * @return GardenUser[] Returns an array of GardenUser objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?GardenUser
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
