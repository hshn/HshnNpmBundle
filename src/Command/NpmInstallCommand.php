<?php

namespace Hshn\NpmBundle\Command;

use Hshn\NpmBundle\Npm\NpmManager;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class NpmInstallCommand extends AbstractNpmCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('hshn:npm:install')
            ->setDescription('npm install')
        ;
    }

    /**
     * @inheritdoc
     */
    public function doExecute(NpmManager $npmManager, InputInterface $input)
    {
        return $npmManager->install();
    }
}
