<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Configuration;

use Dridialaa\SyliusSplioPlugin\Entity\Splio\SplioSettings;
use Dridialaa\SyliusSplioPlugin\Repository\Splio\SplioSettingsRepository;

final readonly class DatabaseSplioConfigProvider implements SplioConfigProviderInterface
{
    public function __construct(
        private SplioSettingsRepository $settingsRepository,
        private SplioConfig $fallbackConfig,
    ) {
    }

    public function getConfig(): SplioConfig
    {
        $settings = $this->settingsRepository->findOneBy([], ['id' => 'ASC']);

        if (!$settings instanceof SplioSettings || '' === $settings->getClientId() || '' === $settings->getClientSecret()) {
            return $this->fallbackConfig;
        }

        return new SplioConfig(
            $settings->getBaseUri(),
            $settings->getClientId(),
            $settings->getClientSecret(),
            $settings->getAuthEndpoint(),
            $settings->getTimeout(),
            $settings->isEnabled(),
        );
    }
}
