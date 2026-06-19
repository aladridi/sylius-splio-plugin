<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Repository\Splio;

use Dridialaa\SyliusSplioPlugin\Entity\Splio\SplioSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SplioSettings>
 */
final class SplioSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SplioSettings::class);
    }

    public function getSettings(): SplioSettings
    {
        $settings = $this->findOneBy([], ['id' => 'ASC']);

        if ($settings instanceof SplioSettings) {
            return $settings;
        }

        $settings = new SplioSettings();
        $this->getEntityManager()->persist($settings);
        $this->getEntityManager()->flush();

        return $settings;
    }
}
