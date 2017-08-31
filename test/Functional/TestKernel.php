<?php

namespace Hshn\NpmBundle\Functional;

use Hshn\NpmBundle\Functional\Bundle\BarBundle\BarBundle;
use Hshn\NpmBundle\Functional\Bundle\FooBundle\FooBundle;
use Hshn\NpmBundle\Functional\Bundle\GulpBundle\GulpBundle;
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
     * @var \SplFileObject
     */
    private $config;

    /**
     * TestKernel constructor.
     *
     * @param string $config
     * @param string $environment
     * @param bool   $debug
     */
    public function __construct($config = __DIR__ . '/config/legacy.yml', $environment = 'test', $debug = true)
    {
        $this->config = new \SplFileObject($config);

        parent::__construct($environment, $debug);
    }


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
            new GulpBundle(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getCacheDir()
    {
        return parent::getCacheDir().'-'.$this->config->getFilename();
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        $name = preg_replace('/^([^.]+)(\.[^.]*)*?$/', '$1', $this->config->getFilename());

        $name = implode('', array_map('ucfirst', preg_split('/[-_]/', $name)));

        return parent::getName().''.$name;
    }

    /**
     * @inheritdoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->config->getPathname());
    }
}
