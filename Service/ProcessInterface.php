<?php

namespace SymfonyBundles\ForkBundle\Service;

interface ProcessInterface
{
    /**
     * @var int The maximum quantity of created child processes.
     */
    const MAX_QUANTITY_PROCESESS = 48;

    /**
     * @var int Defaults quantity of created child processes.
     */
    const DEFAULT_QUANTITY_PROCESESS = 8;

    /**
     * Sets the number of created child processes.
     *
     * @param int $size
     *
     * @return ProcessInterface
     */
    public function size($size);

    /**
     * Forks the currently running process.
     *
     * @param \Closure $closure Callback for the subprocesses.
     *
     * @return ProcessInterface
     */
    public function create(\Closure $closure);

    /**
     * Waits while a forked child is alive.
     *
     * @return ProcessInterface
     */
    public function wait();
}
