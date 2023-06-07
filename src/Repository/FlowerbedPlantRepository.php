<?php

namespace App\Repository;

use App\Entity\FlowerbedPlant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FlowerbedPlant>
 *
 * @method FlowerbedPlant|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlowerbedPlant|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlowerbedPlant[]    findAll()
 * @method FlowerbedPlant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlowerbedPlantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlowerbedPlant::class);
    }

    public function save(FlowerbedPlant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FlowerbedPlant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FlowerbedPlant[] Returns an array of FlowerbedPlant objects
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

//    public function findOneBySomeField($value): ?FlowerbedPlant
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
