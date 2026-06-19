<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Product;

final class SplioProductSyncResult
{
    /**
     * @param array<int, string> $errors
     */
    public function __construct(
        public readonly int $processed,
        public readonly int $succeeded,
        public readonly int $failed,
        public readonly array $errors = [],
    ) {
    }
}
