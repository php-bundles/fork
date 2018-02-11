<?php

namespace SymfonyBundles\Fork;

abstract class Task implements TaskInterface
{
    /**
     * @var int Number of iterations to perform garbage cleaning
     */
    protected $iterations = 100;

    /**
     * Performs garbage collection when the iteration limit is reached.
     */
    protected function iterate()
    {
        static $currentIteration = 0;

        if (false === gc_enabled()) {
            gc_enable();
        }

        if (++$currentIteration >= $this->iterations) {
            $currentIteration = 0;

            gc_collect_cycles();
        }
    }
}
