<?php

namespace SymfonyBundles\Tests\Task;

use SymfonyBundles\Fork;

class DemoTask extends Fork\Task
{
    public const GARBAGE_COLLECT_ITERATIONS = 1;

    private $isExecuted = false;

    public function isExecuted(): bool
    {
        return $this->isExecuted;
    }

    public function execute(Fork\ProcessInterface $process): void
    {
        $this->isExecuted = true;

        for ($i = 0; $i < 20; ++$i) {
            $object = new \stdClass();
            $object->reference = $object;

            $this->foo($object);

            $this->garbageCollect();
        }
    }

    protected function foo(\stdClass &$baz)
    {
        $baz->bar = range(0, 100000);
    }
}
