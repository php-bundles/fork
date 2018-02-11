<?php

namespace SymfonyBundles\ForkBundle\Tests\Fork;

use SymfonyBundles\ForkBundle\Fork;
use SymfonyBundles\ForkBundle\Tests\TestCase;

class ProcessTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Fork\ProcessInterface::class, new Fork\Process());
    }

    public function testProcessesCount()
    {
        $process = new Fork\Process();
        $reflection = new \ReflectionObject($process);
        $property = $reflection->getProperty('processesCount');

        $property->setAccessible(true);

        $process->setCountOfChildProcesses(-123);
        $this->assertSame(1, $property->getValue($process));

        $process->setCountOfChildProcesses(Fork\ProcessInterface::MAX_PROCESSES_QUANTITY + 1);
        $this->assertSame(Fork\ProcessInterface::MAX_PROCESSES_QUANTITY, $property->getValue($process));

        $process->setCountOfChildProcesses(8);
        $this->assertSame(8, $property->getValue($process));
    }

    public function testCreate()
    {
        $process = $this->getMockBuilder(Fork\Process::class)
            ->setMethods(['fork', 'terminate'])
            ->getMock();

        $process->method('fork')->willReturn(true);

        $filename = $this->container->getParameter('kernel.cache_dir') . '/unit-test.create';

        file_put_contents($filename, 0);

        $processesCount = mt_rand(1, Fork\ProcessInterface::MAX_PROCESSES_QUANTITY);

        $process->setCountOfChildProcesses($processesCount)->create(function () use ($filename) {
            file_put_contents($filename, file_get_contents($filename) + 1);
        })->wait();

        $this->assertEquals($processesCount, file_get_contents($filename));
    }

    public function testFakeChild()
    {
        $process = $this->getMockBuilder(Fork\Process::class)
            ->setMethods(['fork', 'terminate'])
            ->getMock();

        $process->method('fork')->willReturn(true);

        $process->create(function () {
        });
    }
}
