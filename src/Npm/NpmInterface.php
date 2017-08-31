<?php

namespace Hshn\NpmBundle\Npm;

use Symfony\Component\Process\Process;

/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
interface NpmInterface
{
    /**
     * @param array                  $commands
     * @param ConfigurationInterface $configuration
     *
     * @return Process
     */
    public function run(array $commands, ConfigurationInterface $configuration);

    /**
     * @param array                  $commands
     * @param ConfigurationInterface $configuration
     *
     * @return Process
     */
    public function install(array $commands, ConfigurationInterface $configuration);
}
