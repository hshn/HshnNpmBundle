<?php

namespace Hshn\NpmBundle\Npm;

use Symfony\Component\Process\Process;

/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class NpmManager
{
    /**
     * @var ConfigurationInterface[]
     */
    private $bundles;

    /**
     * NpmManager constructor.
     *
     * @param ConfigurationInterface[] $bundles
     */
    public function __construct(array $bundles)
    {
        $this->bundles = array_reduce($bundles, function (array $bundles, ConfigurationInterface $configuration) {
            return $bundles + [$configuration->getName() => $configuration];
        }, []);
    }


    /**
     * @param array $commands
     *
     * @return Process[]
     */
    public function install(array $commands = [])
    {
        return $this->each(function (ConfigurationInterface $configuration) use ($commands) {
            return $configuration->install($commands);
        });
    }

    /**
     * @param array $commands
     *
     * @return Process[]
     */
    public function run(array $commands = [])
    {
        return $this->each(function (ConfigurationInterface $configuration) use ($commands) {
            return $configuration->run($commands);
        });
    }

    /**
     * @param callable $action
     *
     * @return Process[]
     */
    private function each(callable $action)
    {
        return array_reduce($this->bundles, function (array $processes, ConfigurationInterface $configuration) use ($action) {
            return array_merge($processes, [$configuration->getName() => $action($configuration)]);
        }, []);
    }

    /**
     * @param array $bundles
     *
     * @return NpmManager
     */
    public function bundles(array $bundles)
    {
        $invalidBundles = array_filter($bundles, function ($bundle) {
            return !$this->isExistentBundle($bundle);
        });

        if ($invalidBundles) {
            throw new \InvalidArgumentException(sprintf('There are no bundles: %s', implode(', ', $invalidBundles)));
        }

        $bundles = array_map(function ($name) {
            return $this->bundles[$name];
        }, $bundles);

        return new NpmManager($bundles);
    }

    /**
     * @param string $bundle
     *
     * @return bool
     */
    private function isExistentBundle($bundle)
    {
        return isset($this->bundles[$bundle]);
    }
}
