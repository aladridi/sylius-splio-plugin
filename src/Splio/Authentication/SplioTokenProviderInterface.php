<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Authentication;

interface SplioTokenProviderInterface
{
    public function getAccessToken(): string;
}
