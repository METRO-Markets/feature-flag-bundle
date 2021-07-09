<?php declare(strict_types=1);

namespace MetroMarkets\FFBundle\Tests;

use MetroMarkets\FFBundle\FeatureFlagService;
use MetroMarkets\FFBundle\Providers\ConfigCatProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ConfigurationTest extends TestCase
{
    public function testServiceWiring()
    {
        $kernel = new TestingKernel();
        $kernel->boot();

        $container = $kernel->getContainer();

        /** @var FeatureFlagService $service */
        $service = $container->get(FeatureFlagService::class);

        $this->assertInstanceOf(FeatureFlagService::class, $service);
    }

    public function testConfigCatProviderWillFailIfConfigIsNotSet()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Configcat config must be set when using provider:configcat');

        $kernel = new TestingKernel([
            'provider' => 'configcat',
        ]);

        $kernel->boot();
    }

    public function testConfigCatProviderWillFailIfSdkKeyIsNotSet()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Configcat sdk_key must be set');

        $kernel = new TestingKernel([
            'provider' => 'configcat',
            'configcat' => [
            ],
        ]);

        $kernel->boot();
    }

    public function testConfigCatProviderWillSucceedWithCorrectConfig()
    {
        $kernel = new TestingKernel([
            'provider' => 'configcat',
            'configcat' => [
                'sdk_key' => 'key'
            ],
        ]);

        $kernel->boot();

        $container = $kernel->getContainer();

        /** @var FeatureFlagService $service */
        $service = $container->get(FeatureFlagService::class);

        $this->assertInstanceOf(FeatureFlagService::class, $service);

        $configCatProvider = $this->getValueFromReflection($service, 'provider');

        $this->assertInstanceOf(ConfigCatProvider::class, $configCatProvider);
    }

    private function getValueFromReflection(object $object, $propertyName)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }
}
