<?php

namespace SymfonyBundles\ForkBundle\Tests\Service;

use SymfonyBundles\ForkBundle\Tests\TestCase;
use SymfonyBundles\ForkBundle\Service\Process;
use SymfonyBundles\ForkBundle\Service\ProcessInterface;

class ProcessTest extends TestCase
{

    public function testInterface()
    {
        $this->assertInstanceOf(ProcessInterface::class, new Process);
    }

    public function testSize()
    {
        $process = new Process;
        $reflection = new \ReflectionObject($process);
        $property = $reflection->getProperty('size');

        $property->setAccessible(true);

        $process->size(-123);
        $this->assertSame(1, $property->getValue($process));

        $process->size(ProcessInterface::MAX_QUANTITY_PROCESESS + 1);
        $this->assertSame(ProcessInterface::MAX_QUANTITY_PROCESESS, $property->getValue($process));

        $process->size(8);
        $this->assertSame(8, $property->getValue($process));
    }

    public function testCreate()
    {
        $process = $this->getMockBuilder(Process::class)
            ->setMethods(['fork', 'terminate'])
            ->getMock();

        $process->method('fork')->willReturn(true);

        $filename = $this->container->getParameter('kernel.cache_dir') . '/unit-test.create';

        file_put_contents($filename, 0);

        $size = mt_rand(1, ProcessInterface::MAX_QUANTITY_PROCESESS);

        $process->size($size)->create(function() use($filename) {
            file_put_contents($filename, file_get_contents($filename) + 1);
        })->wait();

        $this->assertEquals($size, file_get_contents($filename));
    }

    public function testFakeChild()
    {
        $process = $this->getMockBuilder(Process::class)
            ->setMethods(['fork', 'terminate'])
            ->getMock();

        $process->method('fork')->willReturn(true);

        $process->create(function() {
            //
        });
    }

}
