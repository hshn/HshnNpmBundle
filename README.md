# HshnNpmBundle

[![Build Status](https://travis-ci.org/hshn/HshnNpmBundle.svg?branch=master)](https://travis-ci.org/hshn/HshnNpmBundle) [![Latest Stable Version](https://poser.pugx.org/hshn/npm-bundle/v/stable)](https://packagist.org/packages/hshn/npm-bundle) [![Latest Unstable Version](https://poser.pugx.org/hshn/npm-bundle/v/unstable)](https://packagist.org/packages/hshn/npm-bundle)

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

Place your `package.json` in the npm directory, the default value for the npm dir is `$yourBundle/Resources/npm`.

#### Example:

```json
{
    "name": "your-bundle-name",
    "dependencies": {
        "lodash": "^4.3.0"
    }
}
```
Now run the command `app/console hshn:npm:install` to install all the dependencies in the npm directory ``$yourBundle/Resources/npm/npde_modules`.

### 5. Use the installed node modules in your build scripts

Write build scripts you like.

> gulp example is [here](https://github.com/hshn/HshnNpmBundle/tree/master/test/Functional/Bundle/GulpBundle/Resources/npm)

### 6. Register your build scripts as a npm script

```json
{
    "name": "your-bundle-name",
    "dependencies": {
        "lodash": "^4.3.0"
    },
    "scripts": {
        "build": "gulp build"
    }
}
```

then you can run the npm script by using following command:

```bash
$ php app/console hshn:npm:run build # `npm run build` in every bundles
```
