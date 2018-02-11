<?php

namespace SymfonyBundles\ForkBundle\Fork;

abstract class Task implements TaskInterface
{
    /**
     * @var int Number of iterations to perform garbage cleaning
     */
    protected $iterations = 100;

    /**
     * @var int Number of iterations to perform garbage cleaning
     */
    private $currentIteration = 0;

    /**
     * Performs garbage collection when the iteration limit is reached.
     */
    protected function iterate(): void
    {
        if (false === gc_enabled()) {
            gc_enable();
        }

        if (++$this->currentIteration >= $this->iterations) {
            $this->currentIteration = 0;

            gc_collect_cycles();
        }
    }
}
