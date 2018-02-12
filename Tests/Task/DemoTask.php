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

        for ($i = 0; $i < 20; ++$i) {
            $object = new \stdClass();
            $object->reference = $object;

            $this->foo($object);

            $this->collectCycles();
        }
    }

    protected function foo(\stdClass &$baz)
    {
        $baz->bar = range(0, 100000);
    }
}
