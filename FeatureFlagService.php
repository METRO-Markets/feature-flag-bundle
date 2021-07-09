<?php declare(strict_types=1);

namespace MetroMarkets\FFBundle;

use MetroMarkets\FFBundle\Contract\ProviderInterface;

class FeatureFlagService
{
    /** @var ProviderInterface */
    private $provider;

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function isEnabled(string $key, $userIdentifier = null): bool
    {
        return $this->provider->isEnabled($key, $userIdentifier);
    }
}
