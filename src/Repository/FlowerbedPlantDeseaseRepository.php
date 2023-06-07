<?php

namespace App\Repository;

use App\Entity\FlowerbedPlantDesease;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FlowerbedPlantDesease>
 *
 * @method FlowerbedPlantDesease|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlowerbedPlantDesease|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlowerbedPlantDesease[]    findAll()
 * @method FlowerbedPlantDesease[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlowerbedPlantDeseaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlowerbedPlantDesease::class);
    }

    public function save(FlowerbedPlantDesease $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FlowerbedPlantDesease $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FlowerbedPlantDesease[] Returns an array of FlowerbedPlantDesease objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FlowerbedPlantDesease
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
