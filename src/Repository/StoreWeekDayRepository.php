<?php

namespace App\Repository;

use App\Entity\StoreWeekDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StoreWeekDay>
 *
 * @method StoreWeekDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoreWeekDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoreWeekDay[]    findAll()
 * @method StoreWeekDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreWeekDayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoreWeekDay::class);
    }

    public function save(StoreWeekDay $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StoreWeekDay $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return StoreWeekDay[] Returns an array of StoreWeekDay objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StoreWeekDay
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
