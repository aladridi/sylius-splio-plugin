<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Authentication;

use Dridialaa\SyliusSplioPlugin\Splio\Configuration\SplioConfigProviderInterface;
use Dridialaa\SyliusSplioPlugin\Splio\Exception\SplioApiException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final readonly class SplioTokenProvider implements SplioTokenProviderInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private CacheInterface $cache,
        private SplioConfigProviderInterface $configProvider,
    ) {
    }

    public function getAccessToken(): string
    {
        $config = $this->configProvider->getConfig();
        $cacheKey = hash('sha256', sprintf('splio.%s.%s', $config->baseUri, $config->clientId));

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($config): string {
            $response = $this->httpClient->request('POST', $config->authEndpoint, [
                'base_uri' => $config->baseUri,
                'timeout' => $config->timeout,
                'json' => [
                    'client_id' => $config->clientId,
                    'client_secret' => $config->clientSecret,
                    'grant_type' => 'client_credentials',
                ],
            ]);

            $data = $this->decodeResponse($response, $config->authEndpoint);
            $token = $data['access_token'] ?? null;

            if (!is_string($token) || '' === $token) {
                throw SplioApiException::invalidResponse($config->authEndpoint);
            }

            $expiresIn = isset($data['expires_in']) && is_numeric($data['expires_in']) ? (int) $data['expires_in'] : 3600;
            $item->expiresAfter(max(60, $expiresIn - 60));

            return $token;
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeResponse(ResponseInterface $response, string $endpoint): array
    {
        $statusCode = $response->getStatusCode();
        $content = $response->getContent(false);

        if ($statusCode < 200 || $statusCode >= 300) {
            throw SplioApiException::requestFailed('POST', $endpoint, $statusCode, $content);
        }

        $data = json_decode($content, true);

        if (!is_array($data)) {
            throw SplioApiException::invalidResponse($endpoint);
        }

        return $data;
    }
}
