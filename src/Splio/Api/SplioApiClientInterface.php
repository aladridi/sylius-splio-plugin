<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Api;

interface SplioApiClientInterface
{
    /**
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>
     */
    public function request(string $method, string $endpoint, array $payload = []): array;
}
