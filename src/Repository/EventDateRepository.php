<?php

namespace App\Repository;

use App\Entity\EventDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventDate>
 */
class EventDateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventDate::class);
    }

    public function deactivateAllExcept(int $keepId): void
    {
        $this->getEntityManager()->createQuery(
            'UPDATE App\Entity\EventDate d SET d.isActive = false WHERE d.id != :id'
        )->setParameter('id', $keepId)->execute();
    }

    public function deactivateAll(): void
    {
        $this->getEntityManager()->createQuery(
            'UPDATE App\Entity\EventDate d SET d.isActive = false'
        )->execute();
    }

    public function findActiveOne(): ?EventDate
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('e.startDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne toutes les dates avec agendas (join fetch), triés :
     * - EventDate par startDate ASC
     * - Agenda par startTime ASC
     */
    public function findAllWithAgendasOrdered(): array
    {
        return $this->createQueryBuilder('e')
            ->select('DISTINCT e, a')
            ->leftJoin('e.agendas', 'a')
            ->addSelect('a')
            ->orderBy('e.startDate', 'ASC')
            ->addOrderBy('a.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return EventDate[] Returns an array of EventDate objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EventDate
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
