<?php

namespace App\Repository;

use App\Entity\GroundAcidity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroundAcidity>
 *
 * @method GroundAcidity|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroundAcidity|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroundAcidity[]    findAll()
 * @method GroundAcidity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroundAcidityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroundAcidity::class);
    }

    public function save(GroundAcidity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GroundAcidity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return GroundAcidity[] Returns an array of GroundAcidity objects
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

//    public function findOneBySomeField($value): ?GroundAcidity
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
