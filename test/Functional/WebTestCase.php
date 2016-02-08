<?php

namespace Hshn\NpmBundle\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class WebTestCase extends BaseWebTestCase
{
    protected static function getKernelClass()
    {
        return TestKernel::class;
    }
}
