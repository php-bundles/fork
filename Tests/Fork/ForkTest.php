<?php

namespace SymfonyBundles\Tests\Fork;

use SymfonyBundles\Fork;
use PHPUnit\Framework\TestCase;
use SymfonyBundles\Tests\Task\DemoTask;

class ForkTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Fork\ForkInterface::class, new Fork\Fork());
    }

    public function testTasksPoll()
    {
        $task = new DemoTask();
        $fork = new Fork\Fork();

        $fork->attach(new DemoTask())->attach($task);

        $this->assertTrue($fork->exists($task));
        $this->assertFalse($fork->exists(new DemoTask()));

        $this->assertFalse($fork->detach($task)->exists($task));

        gc_disable();

        $fork->run();
    }

    public function testTasksExecuting()
    {
        $process = $this->getMockBuilder(Fork\Process::class)
            ->setMethods(['fork', 'terminate'])
            ->getMock();

        $process->method('fork')->willReturn(true);

        $fork = new Fork\Fork($process);

        $fork
            ->attach($task1 = new DemoTask())
            ->attach($task2 = new DemoTask())
            ->attach($task3 = new DemoTask());

        $fork->run();

        $this->assertTrue($task1->isExecuted());
        $this->assertTrue($task2->isExecuted());
        $this->assertTrue($task3->isExecuted());
    }
}
