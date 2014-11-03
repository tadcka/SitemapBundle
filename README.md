SitemapBundle
=============

[![Build Status](https://scrutinizer-ci.com/g/tadcka/SitemapBundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tadcka/SitemapBundle/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tadcka/SitemapBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tadcka/SitemapBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/tadcka/SitemapBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tadcka/SitemapBundle/?branch=master)

Simple web page list manager.

## Installation

### Step 1: Download SitemapBundle using composer

Add SitemapBundle in your composer.json:

```js
{
    "require": {
        "tadcka/sitemap-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update tadcka/sitemap-bundle
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Tadcka\Bundle\SitemapBundle\TadckaSitemapBundle(),
    );
}
```

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

Code License:
[Resources/meta/LICENSE](https://github.com/tadcka/SitemapBundle/blob/master/Resources/meta/LICENSE)
