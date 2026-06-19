<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\ValueObject;

final readonly class SplioContact
{
    /**
     * @param array<string, mixed> $customFields
     */
    public function __construct(
        public string $email,
        public ?string $firstname = null,
        public ?string $lastname = null,
        public array $customFields = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return array_filter([
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'custom_fields' => $this->customFields,
        ], static fn (mixed $value): bool => null !== $value && [] !== $value);
    }
}
