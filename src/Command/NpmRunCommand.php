<?php

namespace Hshn\NpmBundle\Command;

use Hshn\NpmBundle\Npm\NpmManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class NpmRunCommand extends AbstractNpmCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('hshn:npm:run')
            ->setDescription('npm run')
            ->addArgument('npm-command', InputArgument::REQUIRED, 'npm command')
        ;
    }

    /**
     * @inheritDoc
     */
    public function doExecute(NpmManager $npmManager, InputInterface $input)
    {
        return $npmManager->run([$input->getArgument('npm-command')]);
    }
}
