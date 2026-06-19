<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Configuration;

interface SplioConfigProviderInterface
{
    public function getConfig(): SplioConfig;
}
