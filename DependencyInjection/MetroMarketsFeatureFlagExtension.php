<?php declare(strict_types=1);

namespace MetroMarkets\FFBundle\DependencyInjection;


use MetroMarkets\FFBundle\FeatureFlagService;
use MetroMarkets\FFBundle\Providers\ConfigCatProvider;
use MetroMarkets\FFBundle\Providers\FalseProvider;
use MetroMarkets\FFBundle\Providers\RandomProvider;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class MetroMarketsFeatureFlagExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $adapter = $config['provider'];

        switch ($adapter) {
            case 'configcat':
                $this->configureConfigCat($container, $config);
                break;
            case 'random':
                $this->configureService($container, RandomProvider::class);
                break;
            default:
                $this->configureService($container, FalseProvider::class);
                break;
        }
    }

    private function configureConfigCat(ContainerBuilder $container, array $config)
    {
        $adapterDefinition = $container->getDefinition(ConfigCatProvider::class);

        if (!isset($config['configcat'])) {
            throw new InvalidConfigurationException('Configcat config must be set when using provider:configcat');
        }

        if (!class_exists('ConfigCat\ConfigCatClient')) {
            throw new InvalidConfigurationException('Configcat client need to be installed. Please execute `composer require configcat/configcat-client`');
        }

        if (!isset($config['configcat']['sdk_key'])) {
            throw new InvalidConfigurationException('Configcat sdk_key must be set');
        }

        $adapterDefinition->setArgument(0, $config['configcat']['sdk_key']);
        $adapterDefinition->setArgument(1, isset($config['configcat']['cache']['driver']) ? new Reference($config['configcat']['cache']['driver']) : null);
        $adapterDefinition->setArgument(2, $config['configcat']['cache']['ttl'] ?? 60);
        $adapterDefinition->setArgument(3, isset($config['configcat']['logger']) ? new Reference($config['configcat']['logger']) : null);


        $this->configureService($container, ConfigCatProvider::class);
    }

    private function configureService(ContainerBuilder $container, string $provider)
    {
        $serviceDefinition = $container->getDefinition(FeatureFlagService::class);
        $serviceDefinition->setArgument(0, new Reference($provider));
    }
}
