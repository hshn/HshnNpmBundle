<?php

namespace Hshn\NpmBundle\Npm;

use Symfony\Component\Process\Process;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
interface ConfigurationInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * The configuration directory
     *
     * @return \SplFileInfo
     */
    public function getDirectory();

    /**
     * @param array $commands
     *
     * @return Process
     */
    public function install(array $commands);

    /**
     * @param array $commands
     *
     * @return Process
     */
    public function run(array $commands);
}
