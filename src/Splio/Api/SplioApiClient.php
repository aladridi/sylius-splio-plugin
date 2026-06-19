<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Api;

use Dridialaa\SyliusSplioPlugin\Splio\Authentication\SplioTokenProviderInterface;
use Dridialaa\SyliusSplioPlugin\Splio\Configuration\SplioConfigProviderInterface;
use Dridialaa\SyliusSplioPlugin\Splio\Exception\SplioApiException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class SplioApiClient implements SplioApiClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private SplioTokenProviderInterface $tokenProvider,
        private SplioConfigProviderInterface $configProvider,
    ) {
    }

    public function request(string $method, string $endpoint, array $payload = []): array
    {
        $config = $this->configProvider->getConfig();

        if (!$config->enabled) {
            throw new SplioApiException('Splio CRM integration is disabled.');
        }

        $options = [
            'base_uri' => $config->baseUri,
            'timeout' => $config->timeout,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => sprintf('Bearer %s', $this->tokenProvider->getAccessToken()),
            ],
        ];

        if ([] !== $payload) {
            $options['json'] = $payload;
        }

        $response = $this->httpClient->request($method, $endpoint, $options);
        $statusCode = $response->getStatusCode();
        $content = $response->getContent(false);

        if ($statusCode < 200 || $statusCode >= 300) {
            throw SplioApiException::requestFailed($method, $endpoint, $statusCode, $content);
        }

        if ('' === $content) {
            return [];
        }

        $data = json_decode($content, true);

        if (!is_array($data)) {
            throw SplioApiException::invalidResponse($endpoint);
        }

        return $data;
    }
}
