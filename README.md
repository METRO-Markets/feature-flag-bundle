
[![Build Status](https://github.com/METRO-Markets/feature-flag-bundle/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/METRO-Markets/feature-flag-bundle/actions/workflows/continuous-integration.yml)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/leobeal/ff/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/leobeal/ff/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/leobeal/ff/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/leobeal/ff/?branch=main)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Metro Markets FF
===============

Metro Markets FF is a Feature Flag Symfony Bundle. It easily allows you to configure and use your favorite feature flag provider.

Installation
----------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require metro-markets/feature-flag-bundle
```

### Step 2: Enable the Bundle
(Please skip this step if you are using Symfony Flex)
Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    MetroMarkets\FFBundle\MetroMarketsFeatureFlagBundle::class => ['all' => true],
];
```

### Step 3: Create the configuration

```yaml
# config/packages/metro_markets_feature_flag.yaml

metro_markets_feature_flag:
  provider: 'configcat'
  configcat:
    sdk_key: 'PKDVCLf-Hq-h-kCzMp-L7Q/HhOWfwVtZ0mb30i9wi17GQ' # Get it from ConfigCat Dashboard.
    cache:
      driver: 'cache.app'
      ttl: 60
    logger: 'monolog.logger'
```

How to use it
---------------

After the installation is done you can simply inject the service anywhere and use it as follows:

```php
use MetroMarkets\FFBundle\FeatureFlagService;

class AnyService
{
    /** @var FeatureFlagService */
    private $featureFlagService;

    public function __construct(FeatureFlagService $featureFlagService)
    {
        $this->featureFlagService = $featureFlagService;
    }

    protected function someMethod()
    {
        $isEnabled = $this->featureFlagService->isEnabled('isMyAwesomeFeatureEnabled');
        
        if ($isEnabled){
            doTheNewStuff();
        } else{
            keepTheOld();
        }
    }   
}
```

About providers
---------------

Currently, the only supported provider is configCat. Please, refer to the [official PHP SDK](https://github.com/configcat/php-sdk) for more details.


License
-------
This package is open-sourced software licensed under the MIT license. Please for more info refer to the [license](LICENSE)