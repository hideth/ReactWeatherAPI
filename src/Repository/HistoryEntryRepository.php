<?php

namespace App\Repository;

use App\Entity\HistoryEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HistoryEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoryEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoryEntry[]    findAll()
 * @method HistoryEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoryEntry::class);
    }

    public function getBasicStats()
    {
        return $this->createQueryBuilder('historyEntry')
            ->select('historyEntry')
            ->addSelect('MAX(historyEntry.temperature) as MAX_TEMPERATURE')
            ->addSelect('MIN(historyEntry.temperature) as MIN_TEMPERATURE')
            ->addSelect('AVG(historyEntry.temperature) as AVG_TEMPERATURE')
            ->addSelect('COUNT(historyEntry.id) as COUNT')
            ->getQuery()->getArrayResult()
        ;
    }

    public function getMostSearchedCity()
    {
        return $this->createQueryBuilder('historyEntry')
            ->addSelect('count(historyEntry.city) as count')
            ->groupBy('historyEntry.city')
            ->orderBy('count', 'DESC')
            ->setMaxResults(1)
            ->getQuery()->getArrayResult()
            ;
    }
}
