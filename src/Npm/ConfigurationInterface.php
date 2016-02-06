<?php

namespace Hshn\NpmBundle\Npm;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
interface ConfigurationInterface
{
    /**
     * The configuration directory
     *
     * @return \SplFileInfo
     */
    public function getDirectory();
}
