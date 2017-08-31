<?php

namespace Hshn\NpmBundle\Npm;
use Symfony\Component\Process\Process;


/**
 * @author Shota Hoshino <sht.hshn@gmail.com>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideTests
     *
     * @param $directory
     */
    public function test($directory)
    {
        $configuration = new Configuration('foo', $directory, $this->mockNpm());
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
     * @dataProvider                   provideInvalidDirectoryTests
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /The configuration directory "[^"]+" for "foo" is not a directory/
     *
     * @param $directory
     */
    public function testThrowExceptionUnlessValidDirectory($directory)
    {
        new Configuration('foo', $directory, $this->mockNpm());
    }

    /**
     * @return array
     */
    public function provideInvalidDirectoryTests()
    {
        return [
            [__FILE__],
            [new \SplFileInfo(__FILE__)],
            [__FILE__ . '/InvalidPath'],
        ];
    }

    /**
     * @dataProvider provideUnexpectedDirectoryTests
     * @expectedException \UnexpectedValueException
     *
     * @param $directory
     */
    public function testThrowExceptionUnlessExpectedDirectory($directory)
    {
        new Configuration('', $directory, $this->mockNpm());
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

    public function testRun()
    {
        $configuration = new Configuration('foo', __DIR__, $npm = $this->mockNpm());

        $args = ['foo' => 'bar'];
        $npm->expects(self::once())->method('run')->with($args)->willReturn($process = $this->mockProcess());

        self::assertSame($process, $configuration->run($args));
    }

    public function testInstall()
    {
        $configuration = new Configuration('foo', __DIR__, $npm = $this->mockNpm());

        $args = ['foo' => 'bar'];
        $npm->expects(self::once())->method('install')->with($args)->willReturn($process = $this->mockProcess());

        self::assertSame($process, $configuration->install($args));
    }

    /**
     * @return NpmInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function mockNpm()
    {
        return $this->createMock(NpmInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Process
     */
    private function mockProcess()
    {
        return $this->createMock(Process::class);
    }
}
