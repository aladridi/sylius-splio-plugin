<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Contact;

use Dridialaa\SyliusSplioPlugin\Splio\ValueObject\SplioContact;

interface SplioContactManagerInterface
{
    /**
     * @return array<string, mixed>
     */
    public function upsert(SplioContact $contact): array;

    /**
     * @return array<string, mixed>
     */
    public function getByEmail(string $email): array;
}
