<?php

namespace Hshn\NpmBundle\Command;

use Hshn\NpmBundle\Npm\NpmManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
abstract class AbstractNpmCommand extends Command
{
    /**
     * @var \Hshn\NpmBundle\Npm\NpmManager
     */
    private $npmManager;

    /**
     * AbstractNpmCommand constructor.
     * @param \Hshn\NpmBundle\Npm\NpmManager $npmManager
     */
    public function __construct(NpmManager $npmManager)
    {
        parent::__construct();

        $this->npmManager = $npmManager;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->addOption('bundle', 'b', InputOption::VALUE_REQUIRED| InputOption::VALUE_IS_ARRAY, 'bundle name', [])
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $npm = $this->npmManager;

        if ($bundles = $input->getOption('bundle')) {
            $npm = $npm->bundles($bundles);
        }

        $processes = $this->doExecute($npm);

        foreach ($processes as $name => $process) {
            $process->start(function ($type, $buffer) use ($name, $output) {
                $output->writeln(sprintf('<info>[%s]</info> %s', $name, $buffer));
            });
        }

        return array_reduce($processes, function ($code, Process $process) {
            return $code + $process->wait();
        }, 0);
    }

    /**
     * @param \Hshn\NpmBundle\Npm\NpmManager $npmManager
     * @return \Symfony\Component\Process\Process[]
     */
    public abstract function doExecute(NpmManager $npmManager);
}
