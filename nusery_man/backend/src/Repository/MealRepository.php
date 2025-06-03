<?php

namespace App\Repository;

use App\Entity\Meal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meal::class);
    }

    public function findByDate(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.date = :date')
            ->setParameter('date', $date)
            ->orderBy('m.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByDateAndType(\DateTimeInterface $date, string $type): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.date = :date')
            ->andWhere('m.type = :type')
            ->setParameter('date', $date)
            ->setParameter('type', $type)
            ->orderBy('m.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}