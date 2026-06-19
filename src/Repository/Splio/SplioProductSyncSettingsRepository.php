<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Repository\Splio;

use Dridialaa\SyliusSplioPlugin\Entity\Splio\SplioProductSyncSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SplioProductSyncSettings>
 */
final class SplioProductSyncSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SplioProductSyncSettings::class);
    }

    public function getSettings(): SplioProductSyncSettings
    {
        $settings = $this->findOneBy([], ['id' => 'ASC']);

        if ($settings instanceof SplioProductSyncSettings) {
            return $settings;
        }

        $settings = new SplioProductSyncSettings();
        $this->getEntityManager()->persist($settings);
        $this->getEntityManager()->flush();

        return $settings;
    }
}
