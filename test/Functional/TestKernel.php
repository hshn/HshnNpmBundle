<?php

namespace Hshn\NpmBundle\Functional;

use Hshn\NpmBundle\Functional\Bundle\BarBundle\BarBundle;
use Hshn\NpmBundle\Functional\Bundle\FooBundle\FooBundle;
use Hshn\NpmBundle\HshnNpmBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class TestKernel extends Kernel
{
    /**
     * @inheritdoc
     */
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new HshnNpmBundle(),
            new FooBundle(),
            new BarBundle(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }
}
