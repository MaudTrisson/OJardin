<?php

namespace App\Repository;

use App\Entity\PlantMaintenanceAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlantMaintenanceAction>
 *
 * @method PlantMaintenanceAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlantMaintenanceAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlantMaintenanceAction[]    findAll()
 * @method PlantMaintenanceAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlantMaintenanceActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlantMaintenanceAction::class);
    }

    public function save(PlantMaintenanceAction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PlantMaintenanceAction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PlantMaintenanceAction[] Returns an array of PlantMaintenanceAction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PlantMaintenanceAction
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
