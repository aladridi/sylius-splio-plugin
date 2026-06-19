<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Contact;

use Dridialaa\SyliusSplioPlugin\Splio\Api\SplioApiClientInterface;
use Dridialaa\SyliusSplioPlugin\Splio\ValueObject\SplioContact;

final readonly class SplioContactManager implements SplioContactManagerInterface
{
    public function __construct(private SplioApiClientInterface $apiClient)
    {
    }

    public function upsert(SplioContact $contact): array
    {
        return $this->apiClient->request('PUT', '/contacts', $contact->toPayload());
    }

    public function getByEmail(string $email): array
    {
        return $this->apiClient->request('GET', sprintf('/contacts/%s', rawurlencode($email)));
    }
}
