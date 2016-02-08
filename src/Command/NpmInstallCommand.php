<?php

namespace Hshn\NpmBundle\Command;

use Hshn\NpmBundle\Npm\NpmManager;

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
    public function doExecute(NpmManager $npmManager)
    {
        return $npmManager->install();
    }
}
