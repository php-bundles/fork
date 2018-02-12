<?php

namespace SymfonyBundles\Fork;

abstract class Task implements TaskInterface
{
    /**
     * @var int Number of iterations to perform garbage cleaning
     */
    public const GARBAGE_COLLECTION_ITERATIONS = 1;

    /**
     * Performs garbage collection when the iteration limit is reached.
     */
    protected function collectCycles()
    {
        static $currentIteration = 0;

        if (false === gc_enabled()) {
            gc_enable();
        }

        if (++$currentIteration >= static::GARBAGE_COLLECTION_ITERATIONS) {
            $currentIteration = 0;

            gc_collect_cycles();
        }
    }
}
