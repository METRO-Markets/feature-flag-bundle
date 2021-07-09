<?php declare(strict_types=1);

namespace MetroMarkets\FFBundle\Tests;


use MetroMarkets\FFBundle\MetroMarketsFeatureFlagBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class TestingKernel extends Kernel
{
    /** @var array */
    private $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        parent::__construct('test', true);
    }

    public function registerBundles()
    {
        return [
            new MetroMarketsFeatureFlagBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('metro_markets_feature_flag', $this->config);
        });
    }

    public function getCacheDir()
    {
        //makes sure that container is not re-used
        return __DIR__ . '/cache/' . spl_object_hash($this);
    }
}
