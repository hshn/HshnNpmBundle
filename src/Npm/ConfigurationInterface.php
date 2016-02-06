<?php

namespace Hshn\NpmBundle\Npm;


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
}
