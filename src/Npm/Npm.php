<?php

namespace Hshn\NpmBundle\Npm;

use Symfony\Component\Process\ProcessBuilder;

/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class Npm
{
    /**
     * @var string
     */
    private $binPath;

    /**
     * Npm constructor.
     * @param string $binPath
     */
    public function __construct($binPath)
    {
        $this->binPath = $binPath;
    }

    /**
     * @param array $commands
     * @param \Hshn\NpmBundle\Npm\ConfigurationInterface $configuration
     * @return \Symfony\Component\Process\Process
     */
    public function run(array $commands, ConfigurationInterface $configuration)
    {
        return $this->createProcess(array_merge(['run'], $commands), $configuration);
    }

    /**
     * @param array $commands
     * @param \Hshn\NpmBundle\Npm\ConfigurationInterface $configuration
     * @return \Symfony\Component\Process\Process
     */
    public function install(array $commands, ConfigurationInterface $configuration)
    {
        return $this->createProcess(array_merge(['install'], $commands), $configuration);
    }

    /**
     * @param array $commands
     * @param \Hshn\NpmBundle\Npm\ConfigurationInterface $configuration
     * @return \Symfony\Component\Process\Process
     */
    private function createProcess(array $commands, ConfigurationInterface $configuration)
    {
        $builder = new ProcessBuilder([$this->binPath]);
        $builder->setWorkingDirectory($configuration->getDirectory());
        $builder->setTimeout(600);

        foreach ($commands as $command) {
            $builder->add($command);
        }

        return $builder->getProcess();
    }
}
