<?php

// src/Repository/ChildRepository.php
namespace App\Repository;

use App\Entity\Child;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Child>
 */
class ChildRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Child::class);
    }

    /**
     * Trouve tous les enfants actifs avec leurs présences récentes
     */
    public function findActiveChildren(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.presences', 'p')
            ->orderBy('c.lastName', 'ASC')
            ->addOrderBy('c.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche d'enfants par nom ou prénom
     */
    public function findByNameSearch(string $search): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.firstName LIKE :search OR c.lastName LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('c.lastName', 'ASC')
            ->addOrderBy('c.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les enfants par tranche d'âge
     */
    public function findByAgeRange(int $minAge, int $maxAge): array
    {
        $maxBirthDate = new \DateTime(sprintf('-%d years', $minAge));
        $minBirthDate = new \DateTime(sprintf('-%d years', $maxAge + 1));

        return $this->createQueryBuilder('c')
            ->where('c.birthDate BETWEEN :minBirthDate AND :maxBirthDate')
            ->setParameter('minBirthDate', $minBirthDate)
            ->setParameter('maxBirthDate', $maxBirthDate)
            ->orderBy('c.birthDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}