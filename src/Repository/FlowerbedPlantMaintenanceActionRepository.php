<?php

namespace App\Repository;

use App\Entity\FlowerbedPlantMaintenanceAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FlowerbedPlantMaintenanceAction>
 *
 * @method PlantFlowerbedMaintenanceAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlantFlowerbedMaintenanceAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlantFlowerbedMaintenanceAction[]    findAll()
 * @method PlantFlowerbedMaintenanceAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlowerbedPlantMaintenanceActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlowerbedPlantMaintenanceAction::class);
    }

    public function save(FlowerbedPlantMaintenanceAction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FlowerbedPlantMaintenanceAction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FlowerbedPlantMaintenanceAction[] Returns an array of FlowerbedPlantMaintenanceAction objects
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

//    public function findOneBySomeField($value): ?FlowerbedPlantMaintenanceAction
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
