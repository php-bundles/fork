<?php

namespace SymfonyBundles\Tests\Task;

use SymfonyBundles\Fork;

class DemoTask extends Fork\Task
{
    private $isExecuted = false;

    public function isExecuted(): bool
    {
        return $this->isExecuted;
    }

    public function execute()
    {
        $this->isExecuted = true;

        for ($i = 0; $i < 200; $i++) {
            $this->iterate();
        }
    }
}
