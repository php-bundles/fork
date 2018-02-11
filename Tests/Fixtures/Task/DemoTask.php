<?php

namespace SymfonyBundles\ForkBundle\Tests\Fixtures\Task;

use SymfonyBundles\ForkBundle\Fork\TaskInterface;

class DemoTask implements TaskInterface
{
    public function execute(): void
    {
    }
}
