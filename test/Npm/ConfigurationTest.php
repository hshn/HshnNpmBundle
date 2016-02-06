<?php

namespace Hshn\NpmBundle\Npm;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideTests
     * @param $directory
     */
    public function test($directory)
    {
        $configuration = new Configuration('foo', $directory);
        $this->assertEquals('foo', $configuration->getName());
        $this->assertInstanceOf(ConfigurationInterface::class, $configuration);
    }

    /**
     * @return array
     */
    public function provideTests()
    {
        return [
            [__DIR__],
            [new \SplFileInfo(__DIR__)],
        ];
    }

    /**
     * @dataProvider provideInvalidDirectoryTests
     * @param $directory
     */
    public function testThrowExceptionUnlessValidDirectory($directory)
    {
        $this->setExpectedExceptionRegExp(\InvalidArgumentException::class);
        new Configuration('', $directory);
    }

    /**
     * @return array
     */
    public function provideInvalidDirectoryTests()
    {
        return [
            [__FILE__],
            [new \SplFileInfo(__FILE__)],
            [__FILE__.'/InvalidPath'],
        ];
    }

    /**
     * @dataProvider provideUnexpectedDirectoryTests
     * @param $directory
     */
    public function testThrowExceptionUnlessExpectedDirectory($directory)
    {
        $this->setExpectedExceptionRegExp(\UnexpectedValueException::class);
        new Configuration('', $directory);
    }

    /**
     * @return array
     */
    public function provideUnexpectedDirectoryTests()
    {
        return [
            [new \stdClass()],
            [1234],
        ];
    }
}
