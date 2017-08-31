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
     * @var NpmInterface
     */
    private $npm;

    /**
     * Configuration constructor.
     *
     * @param string              $name
     * @param \SplFileInfo|string $directory
     * @param NpmInterface        $npm
     */
    public function __construct($name, $directory, NpmInterface $npm)
    {
        $this->name = $name;

        if (is_string($directory)) {
            $directory = new \SplFileInfo($directory);
        } elseif (!$directory instanceof \SplFileInfo) {
            throw new \UnexpectedValueException(sprintf('Expected string or SplFileInfo, %s given', is_object($directory) ? get_class($directory) : gettype($directory)));
        }

        if (!$directory->isDir()) {
            throw new \InvalidArgumentException(sprintf('The configuration directory "%s" for "%s" is not a directory', $directory->getPathname(), $name));
        }

        $this->directory = $directory;
        $this->npm = $npm;
    }

    /**
     * @inheritDoc
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

    /**
     * {@inheritdoc}
     */
    public function install(array $commands)
    {
        return $this->npm->install($commands, $this);
    }

    /**
     * {@inheritdoc}
     */
    public function run(array $commands)
    {
        return $this->npm->run($commands, $this);
    }
}
