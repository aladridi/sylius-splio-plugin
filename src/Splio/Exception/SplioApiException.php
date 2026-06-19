<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Exception;

use RuntimeException;

final class SplioApiException extends RuntimeException
{
    public static function requestFailed(string $method, string $endpoint, int $statusCode, string $response): self
    {
        return new self(sprintf('Splio API request %s %s failed with status %d: %s', $method, $endpoint, $statusCode, $response));
    }

    public static function invalidResponse(string $endpoint): self
    {
        return new self(sprintf('Splio API returned an invalid response for endpoint %s.', $endpoint));
    }
}
