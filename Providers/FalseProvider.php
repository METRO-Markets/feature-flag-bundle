<?php declare(strict_types=1);

namespace MetroMarkets\FFBundle\Providers;

use MetroMarkets\FFBundle\Contract\ProviderInterface;

class FalseProvider implements ProviderInterface
{
    public function isEnabled(string $key, string $userIdentifier = null): bool
    {
        return false;
    }
}
