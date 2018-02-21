<?php

namespace SymfonyBundles\Tests\Fork;

use SymfonyBundles\Fork;
use PHPUnit\Framework\TestCase;

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

        $processesCount = $reflection->getProperty('processesCount');
        $processesCount->setAccessible(true);

        $allowedFork = $reflection->getProperty('allowedFork');
        $allowedFork->setAccessible(true);
        $allowedFork->getValue($process);

        $process->setCountOfChildProcesses(1);
        $this->assertSame(1, $processesCount->getValue($process));

        $process->setCountOfChildProcesses(Fork\ProcessInterface::MAX_PROCESSES_QUANTITY + 1);

        if ($allowedFork->getValue($process)) {
            $this->assertSame(Fork\ProcessInterface::MAX_PROCESSES_QUANTITY, $processesCount->getValue($process));
        } else {
            $this->assertSame(1, $processesCount->getValue($process));
        }

        $process->setCountOfChildProcesses(8);
        $this->assertSame(function_exists('pcntl_fork') ? 8 : 1, $processesCount->getValue($process));
    }

    public function testCreate()
    {
        $process = $this->getMockBuilder(Fork\Process::class)
            ->setMethods(['fork', 'terminate'])
            ->getMock();

        $process->method('fork')->willReturn(true);

        $filename = '/tmp/symfony-cache-unit-test.create';

        file_put_contents($filename, 0);

        $processesCount = mt_rand(1, Fork\ProcessInterface::MAX_PROCESSES_QUANTITY);

        $process->setCountOfChildProcesses($processesCount)->create(function () use ($filename) {
            file_put_contents($filename, file_get_contents($filename) + 1);
        })->wait();

        $this->assertEquals(function_exists('pcntl_fork') ? $processesCount : 1, file_get_contents($filename));
    }

    public function testForkDisabled()
    {
        $process = new Fork\Process();
        $reflection = new \ReflectionObject($process);

        $property = $reflection->getProperty('allowedFork');
        $property->setAccessible(true);
        $property->setValue($process, false);

        $method = $reflection->getMethod('fork');
        $method->setAccessible(true);

        $process->setCountOfChildProcesses(2);

        $this->assertTrue($method->invoke($process));
    }

    public function testIsAliveProcess()
    {
        $process = new Fork\Process();

        $this->assertTrue($process->isAlive($process->getPid()));
    }

    public function testMemoryUsage()
    {
        $process = new Fork\Process();

        $startMemoryUsage = $process->getMemoryUsage();

        $list = range(1, 16400);

        $finishMemoryUsage = $process->getMemoryUsage();

        $this->assertCount(16400, $list);

        $this->assertTrue($finishMemoryUsage > $startMemoryUsage + 1);
    }
}
