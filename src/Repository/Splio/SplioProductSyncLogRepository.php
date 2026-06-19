<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Repository\Splio;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dridialaa\SyliusSplioPlugin\Entity\Splio\SplioProductSyncLog;

/**
 * @extends ServiceEntityRepository<SplioProductSyncLog>
 */
final class SplioProductSyncLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SplioProductSyncLog::class);
    }

    /**
     * @return array<int, SplioProductSyncLog>
     */
    public function findLatest(int $limit = 10): array
    {
        return $this->findBy([], ['createdAt' => 'DESC'], $limit);
    }
}
