<?php declare(strict_types=1);

namespace MetroMarkets\FFBundle\Providers;

use GuzzleHttp\Client;
use MetroMarkets\FFBundle\Contract\ProviderInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class RestProvider implements ProviderInterface
{
    /** @var Client */
    private $client;

    /** @var string */
    private $endpoint;

    /** @var AdapterInterface */
    private $cache;

    /** @var int */
    private $cacheTTL;

    public function __construct(string $endpoint, AdapterInterface $cache = null, int $cacheTTL = 60)
    {
        $this->client = new Client();
        $this->endpoint = $endpoint;
        $this->cache = $cache;
        $this->cacheTTL = $cacheTTL;
    }

    public function isEnabled(string $key, string $userIdentifier = null): bool
    {
        if (!$this->cache) {
            return $this->fetchData($key);
        }

        return $this->getCachedResponse($key);
    }

    private function getCachedResponse(string $key)
    {
        $cacheItem = $this->cache->getItem($key);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $result = $this->fetchData($key);

        $cacheItem->set($result);
        $cacheItem->expiresAfter($this->cacheTTL);
        $this->cache->save($cacheItem);

        return $cacheItem->get();
    }

    private function fetchData(string $key)
    {
        $response = $this->client->get($this->endpoint . '?key=' . $key);

        return \json_decode($response->getBody()->getContents(), true);
    }
}
