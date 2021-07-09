<?php declare(strict_types=1);

namespace MetroMarkets\FFBundle\Contract;

interface ProviderInterface
{
    public function isEnabled(string $key, string $userIdentifier = null): bool;
}

