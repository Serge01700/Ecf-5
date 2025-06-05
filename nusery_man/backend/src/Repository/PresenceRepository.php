<?php

namespace App\Repository;

use App\Entity\Child;
use App\Entity\Presence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Presence>
 */
class PresenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Presence::class);
    }

    /**
     * Trouve les présences d'un enfant pour un mois donné
     */
    public function findByChildAndMonth(Child $child, int $year, int $month): array
    {
        $startDate = new \DateTime(sprintf('%d-%02d-01', $year, $month));
        $endDate = clone $startDate;
        $endDate->modify('last day of this month');

        return $this->createQueryBuilder('p')
            ->where('p.child = :child')
            ->andWhere('p.date BETWEEN :startDate AND :endDate')
            ->setParameter('child', $child)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('p.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Supprime les présences d'un enfant pour un mois donné
     */
    public function deleteByChildAndMonth(Child $child, int $year, int $month): int
    {
        $startDate = new \DateTime(sprintf('%d-%02d-01', $year, $month));
        $endDate = clone $startDate;
        $endDate->modify('last day of this month');

        return $this->createQueryBuilder('p')
            ->delete()
            ->where('p.child = :child')
            ->andWhere('p.date BETWEEN :startDate AND :endDate')
            ->setParameter('child', $child)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->execute();
    }

    /**
     * Statistiques annuelles pour un enfant
     */
    public function getYearlyStats(Child $child, int $year): array
    {
        $startDate = new \DateTime(sprintf('%d-01-01', $year));
        $endDate = new \DateTime(sprintf('%d-12-31', $year));

        // Statistiques par mois
        $monthlyStats = $this->createQueryBuilder('p')
            ->select('MONTH(p.date) as month, COUNT(p.id) as days, SUM(p.hours) as totalHours')
            ->where('p.child = :child')
            ->andWhere('p.date BETWEEN :startDate AND :endDate')
            ->setParameter('child', $child)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->getQuery()
            ->getResult();

        // Statistiques globales
        $totalStats = $this->createQueryBuilder('p')
            ->select('COUNT(p.id) as totalDays, SUM(p.hours) as totalHours, AVG(p.hours) as avgHours')
            ->where('p.child = :child')
            ->andWhere('p.date BETWEEN :startDate AND :endDate')
            ->setParameter('child', $child)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleResult();

        return [
            'year' => $year,
            'monthly' => $monthlyStats,
            'total' => $totalStats
        ];
    }

    /**
     * Trouve les présences pour une période donnée
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.child', 'c')
            ->where('p.date BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('p.date', 'ASC')
            ->addOrderBy('c.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Statistiques de présence pour tous les enfants sur une période
     */
    public function getPresenceStatsByPeriod(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('p')
            ->select('c.id as childId, c.firstName, c.lastName, COUNT(p.id) as totalDays, SUM(p.hours) as totalHours')
            ->leftJoin('p.child', 'c')
            ->where('p.date BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('c.id')
            ->orderBy('c.lastName', 'ASC')
            ->addOrderBy('c.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si un enfant a une présence à une date donnée
     */
    public function hasPresenceOnDate(Child $child, \DateTimeInterface $date): bool
    {
        $count = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.child = :child')
            ->andWhere('p.date = :date')
            ->setParameter('child', $child)
            ->setParameter('date', $date)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    /**
     * Récupère les présences récentes (dernières 30 jours)
     */
    public function findRecentPresences(int $limit = 50): array
    {
        $thirtyDaysAgo = new \DateTime('-30 days');

        return $this->createQueryBuilder('p')
            ->leftJoin('p.child', 'c')
            ->where('p.date >= :thirtyDaysAgo')
            ->setParameter('thirtyDaysAgo', $thirtyDaysAgo)
            ->orderBy('p.date', 'DESC')
            ->addOrderBy('c.lastName', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Calcule le total d'heures pour un enfant sur une période
     */
    public function getTotalHoursByPeriod(Child $child, \DateTimeInterface $startDate, \DateTimeInterface $endDate): float
    {
        $result = $this->createQueryBuilder('p')
            ->select('SUM(p.hours) as totalHours')
            ->where('p.child = :child')
            ->andWhere('p.date BETWEEN :startDate AND :endDate')
            ->setParameter('child', $child)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (float) $result : 0.0;
    }
}