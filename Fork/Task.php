<?php

namespace SymfonyBundles\Fork;

abstract class Task implements TaskInterface
{
    /**
     * @var int Number of iterations to perform garbage cleaning
     */
    public const GARBAGE_COLLECT_ITERATIONS = 100;

    /**
     * Performs garbage collection when the iteration limit is reached.
     */
    protected function garbageCollect(): bool
    {
        static $currentIteration = 0;

        if (false === gc_enabled()) {
            gc_enable();
        }

        if (++$currentIteration >= static::GARBAGE_COLLECT_ITERATIONS) {
            $currentIteration = 0;

            gc_collect_cycles();

            return true;
        }

        return false;
    }
}
