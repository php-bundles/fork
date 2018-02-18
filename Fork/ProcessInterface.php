<?php

namespace SymfonyBundles\Fork;

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
     * Sets the task execution mode.
     *
     * @param bool $isAllowedFork If TRUE - tasks are performed in child processes, otherwise - in the current process
     */
    public function setIsAllowedFork(bool $isAllowedFork): void;

    /**
     * Forks the currently running process.
     *
     * @param \Closure $closure Callback for the subprocesses
     *
     * @return ProcessInterface
     */
    public function create(\Closure $closure): ProcessInterface;

    /**
     * Waits while a forked child is alive.
     *
     * @return ProcessInterface
     */
    public function wait(): ProcessInterface;

    /**
     * Returns the process ID.
     *
     * @return int
     */
    public function getPid(): int;

    /**
     * Checks whether the process alive.
     *
     * @param int $pid Process ID
     *
     * @return bool
     */
    public function isAlive(int $pid): bool;

    /**
     * Returns the amount of memory allocated to PHP in megabytes.
     *
     * @return float
     */
    public function getMemoryUsage(): float;

    /**
     * Sets the number of created child processes.
     *
     * @param int $processesCount
     *
     * @return ProcessInterface
     */
    public function setCountOfChildProcesses(int $processesCount): ProcessInterface;
}
