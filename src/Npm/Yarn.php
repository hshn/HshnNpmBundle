<?php


namespace Hshn\NpmBundle\Npm;


use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

class Yarn implements NpmInterface
{
    /**
     * @var string
     */
    private $binPath;

    /**
     * Yarn constructor.
     *
     * @param string $binPath
     */
    public function __construct($binPath)
    {
        $this->binPath = $binPath;
    }

    /**
     * {@inheritdoc}
     */
    public function run(array $commands, ConfigurationInterface $configuration)
    {
        return $this->createProcess(array_merge(['run'], $commands), $configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function install(array $commands, ConfigurationInterface $configuration)
    {
        return $this->createProcess($commands, $configuration);
    }

    /**
     * @param array                  $commands
     * @param ConfigurationInterface $configuration
     *
     * @return Process
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
