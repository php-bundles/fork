<?php

namespace SymfonyBundles\ForkBundle\Tests\Service;

use SymfonyBundles\ForkBundle\Service\Fork;
use SymfonyBundles\ForkBundle\Tests\TestCase;
use SymfonyBundles\ForkBundle\Service\Process;
use SymfonyBundles\ForkBundle\Tests\Task\DemoTask;
use SymfonyBundles\ForkBundle\Service\ForkInterface;

class ForkTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(ForkInterface::class, $this->getFork());
    }

    public function testTasksPoll()
    {
        $task = new DemoTask();
        $fork = $this->getFork();

        $fork->attach(new DemoTask())->attach($task);

        $this->assertTrue($fork->exists($task));
        $this->assertFalse($fork->exists(new DemoTask()));

        $this->assertFalse($fork->detach($task)->exists($task));

        $fork->run();
    }

    public function testTasksExecuting()
    {
        $process = $this->getMockBuilder(Process::class)
            ->setMethods(['fork', 'terminate'])
            ->getMock();

        $process->method('fork')->willReturn(true);

        $fork = new Fork($process);

        $fork
            ->attach(new DemoTask())
            ->attach(new DemoTask())
            ->attach(new DemoTask());

        $fork->run();
    }

    /**
     * @return Fork
     */
    private function getFork()
    {
        return new Fork(new Process());
    }
}
