<?php

namespace Hshn\NpmBundle\Npm;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var \SplFileInfo
     */
    private $directory;

    /**
     * Configuration constructor.
     * @param string $name
     * @param \SplFileInfo|string $directory
     */
    public function __construct($name, $directory)
    {
        $this->name = $name;

        if (is_string($directory)) {
            $directory = new \SplFileInfo($directory);
        } elseif (!$directory instanceof \SplFileInfo) {
            throw new \UnexpectedValueException(sprintf('Expected string or SplFileInfo, %s given', is_object($directory) ? get_class($directory) : gettype($directory)));
        }

        if (!$directory->isDir()) {
            throw new \InvalidArgumentException('The parameter $directory must be a directory');
        }

        $this->directory = $directory;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getDirectory()
    {
        return $this->directory;
    }
}
