<?php

namespace Hshn\NpmBundle\Functional;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class NpmCommandTestCase extends WebTestCase
{
    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        static::bootKernel();
    }

    /**
     * @param array $args
     * @return string output
     */
    protected function runCommand(array $args)
    {
        $app = new Application(static::$kernel);
        $app->setAutoExit(false);
        $code = $app->run(new ArrayInput($args), $out = new BufferedOutput());

        $this->assertEquals(0, $code, sprintf('The command "%s" was failed. (code: %d)', implode(' ', $args), $code));

        return $out->fetch();
    }
}
