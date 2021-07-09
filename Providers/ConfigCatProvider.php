<?php declare(strict_types=1);

namespace MetroMarkets\FFBundle\Providers;

use ConfigCat\Cache\Psr6Cache;
use ConfigCat\ConfigCatClient;
use ConfigCat\User;
use MetroMarkets\FFBundle\Contract\ProviderInterface;
use Psr\Log\LoggerInterface;

class ConfigCatProvider implements ProviderInterface
{
    /** @var ConfigCatClient */
    private $client;

    public function __construct(string $sdkKey, $cache = null, int $cacheTTL = 60, LoggerInterface $logger = null)
    {
        $options = [];

        if ($cache) {
            $cache = new Psr6Cache($cache);
            $options['cache'] = $cache;
            $options['cache-refresh-interval'] = $cacheTTL;
        }

        if ($logger) {
            $options['logger'] = $logger;
        }

        $this->client = new ConfigCatClient($sdkKey, $options);
    }

    public function isEnabled(string $key, string $userIdentifier = null): bool
    {
        $user = null;

        if ($userIdentifier) {
            $user = new User($userIdentifier);
        }

        return $this->client->getValue($key, false, $user);
    }
}
