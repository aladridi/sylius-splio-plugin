<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Configuration;

final readonly class SplioConfig
{
    public function __construct(
        public string $baseUri,
        public string $clientId,
        public string $clientSecret,
        public string $authEndpoint,
        public int $timeout,
        public bool $enabled = false,
    ) {
    }
}
