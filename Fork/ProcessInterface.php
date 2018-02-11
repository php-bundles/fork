<?php

namespace SymfonyBundles\ForkBundle\Fork;

interface ProcessInterface
{
    /**
     * @var int The maximum quantity of created child processes.
     */
    public const MAX_PROCESSES_QUANTITY = 48;

    /**
     * @var int Automatic determination of the number of processes.
     */
    public const AUTO_DETECT_OF_PROCESSES_QUANTITY = -1;

    /**
     * Sets the number of created child processes.
     *
     * @param int $processesCount
     *
     * @return self
     */
    public function setCountOfChildProcesses(int $processesCount): self;

    /**
     * Forks the currently running process.
     *
     * @param \Closure $closure Callback for the subprocesses
     *
     * @return self
     */
    public function create(\Closure $closure): self;

    /**
     * Waits while a forked child is alive.
     *
     * @return self
     */
    public function wait(): self;
}
