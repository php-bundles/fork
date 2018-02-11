<?php

namespace SymfonyBundles\ForkBundle\Tests\Fixtures\Task;

use SymfonyBundles\ForkBundle\Fork\TaskInterface;

class DemoTask implements TaskInterface
{
    private $isExecuted = false;

    public function isExecuted(): bool
    {
        return $this->isExecuted;
    }

    public function execute(): void
    {
        $this->isExecuted = true;
    }
}
