<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Entity\Splio;

use Dridialaa\SyliusSplioPlugin\Repository\Splio\SplioSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SplioSettingsRepository::class)]
#[ORM\Table(name: 'app_splio_settings')]
class SplioSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'base_uri', length: 255)]
    private string $baseUri = 'https://api.splio.com';

    #[ORM\Column(name: 'auth_endpoint', length: 255)]
    private string $authEndpoint = '/oauth/token';

    #[ORM\Column(name: 'client_id', length: 255)]
    private string $clientId = '';

    #[ORM\Column(name: 'client_secret', type: 'text')]
    private string $clientSecret = '';

    #[ORM\Column]
    private int $timeout = 10;

    #[ORM\Column]
    private bool $enabled = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function setBaseUri(string $baseUri): void
    {
        $this->baseUri = rtrim($baseUri, '/');
    }

    public function getAuthEndpoint(): string
    {
        return $this->authEndpoint;
    }

    public function setAuthEndpoint(string $authEndpoint): void
    {
        $this->authEndpoint = '/' . ltrim($authEndpoint, '/');
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): void
    {
        $this->timeout = max(1, $timeout);
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
}
