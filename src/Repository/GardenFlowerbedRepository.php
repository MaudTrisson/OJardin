<?php

namespace App\Repository;

use App\Entity\GardenFlowerbed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GardenFlowerbed>
 *
 * @method GardenFlowerbed|null find($id, $lockMode = null, $lockVersion = null)
 * @method GardenFlowerbed|null findOneBy(array $criteria, array $orderBy = null)
 * @method GardenFlowerbed[]    findAll()
 * @method GardenFlowerbed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GardenFlowerbedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GardenFlowerbed::class);
    }

    public function save(GardenFlowerbed $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GardenFlowerbed $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return GardenFlowerbed[] Returns an array of GardenFlowerbed objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GardenFlowerbed
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
