# HshnNpmBundle

[![Build Status](https://travis-ci.org/hshn/HshnNpmBundle.svg?branch=master)](https://travis-ci.org/hshn/HshnNpmBundle)

The HshnNpmBundle adds support for managing node modules with npm.

Features included:

 - Install node modules in your bundles with one command
 - Invoke npm scripts in your bundles with one command
 - Unit tested

## Installation

### 1. Download HshnNpmBundle using composer

```bash
$ php composer.phar require hshn/npm-bundle
```

### 2. Enable the bundles

```php
<?php

// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Hshn\NpmBundle\HshnNpmBundle(),
    );
}
```

### 3. Configure the HshnNpmBundle

Enable node modules management for your bundles in the `config.yml` file.

```yaml
# app/config/config.yml
hshn_npm:
    bundles:
        YourBundle: ~
```

### 4. Installing node modules

Place your `package.json` in the config directory, the default value for the config dir is `$yourBundle/Resources/npm`.

#### Example:

```json
{
    "name": "your-bundle-name",
    "dependencies": {
        "lodash": "^4.3.0"
    }
}
```
