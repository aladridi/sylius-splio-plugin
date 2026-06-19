<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Entity\Splio;

use Dridialaa\SyliusSplioPlugin\Repository\Splio\SplioProductSyncSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SplioProductSyncSettingsRepository::class)]
#[ORM\Table(name: 'app_splio_product_sync_settings')]
class SplioProductSyncSettings
{
    public const MODE_MANUAL = 'manual';
    public const MODE_SCHEDULED = 'scheduled';
    public const MODE_REALTIME = 'realtime';

    public const FREQUENCY_EVERY_15_MINUTES = 'every_15_minutes';
    public const FREQUENCY_HOURLY = 'hourly';
    public const FREQUENCY_DAILY = 'daily';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private bool $enabled = false;

    #[ORM\Column(name: 'sync_mode', length: 32)]
    private string $syncMode = self::MODE_MANUAL;

    #[ORM\Column(length: 32)]
    private string $frequency = self::FREQUENCY_HOURLY;

    #[ORM\Column(name: 'batch_size')]
    private int $batchSize = 50;

    #[ORM\Column(name: 'include_disabled_products')]
    private bool $includeDisabledProducts = false;

    #[ORM\Column(name: 'include_variants')]
    private bool $includeVariants = true;

    #[ORM\Column(name: 'sync_images')]
    private bool $syncImages = true;

    #[ORM\Column(name: 'sync_prices')]
    private bool $syncPrices = true;

    #[ORM\Column(name: 'last_sync_at', nullable: true)]
    private ?\DateTimeImmutable $lastSyncAt = null;

    #[ORM\Column(name: 'updated_at')]
    private \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getSyncMode(): string
    {
        return $this->syncMode;
    }

    public function setSyncMode(string $syncMode): void
    {
        $this->syncMode = $syncMode;
    }

    public function getFrequency(): string
    {
        return $this->frequency;
    }

    public function setFrequency(string $frequency): void
    {
        $this->frequency = $frequency;
    }

    public function getBatchSize(): int
    {
        return $this->batchSize;
    }

    public function setBatchSize(int $batchSize): void
    {
        $this->batchSize = max(1, $batchSize);
    }

    public function shouldIncludeDisabledProducts(): bool
    {
        return $this->includeDisabledProducts;
    }

    public function isIncludeDisabledProducts(): bool
    {
        return $this->includeDisabledProducts;
    }

    public function setIncludeDisabledProducts(bool $includeDisabledProducts): void
    {
        $this->includeDisabledProducts = $includeDisabledProducts;
    }

    public function shouldIncludeVariants(): bool
    {
        return $this->includeVariants;
    }

    public function isIncludeVariants(): bool
    {
        return $this->includeVariants;
    }

    public function setIncludeVariants(bool $includeVariants): void
    {
        $this->includeVariants = $includeVariants;
    }

    public function shouldSyncImages(): bool
    {
        return $this->syncImages;
    }

    public function isSyncImages(): bool
    {
        return $this->syncImages;
    }

    public function setSyncImages(bool $syncImages): void
    {
        $this->syncImages = $syncImages;
    }

    public function shouldSyncPrices(): bool
    {
        return $this->syncPrices;
    }

    public function isSyncPrices(): bool
    {
        return $this->syncPrices;
    }

    public function setSyncPrices(bool $syncPrices): void
    {
        $this->syncPrices = $syncPrices;
    }

    public function getLastSyncAt(): ?\DateTimeImmutable
    {
        return $this->lastSyncAt;
    }

    public function setLastSyncAt(?\DateTimeImmutable $lastSyncAt): void
    {
        $this->lastSyncAt = $lastSyncAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function touch(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
