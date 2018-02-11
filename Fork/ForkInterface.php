<?php

namespace SymfonyBundles\Fork;

interface ForkInterface
{
    /**
     * Checks the availability of task in a pool.
     *
     * @param TaskInterface $task
     *
     * @return bool Returns TRUE if exist, else FALSE
     */
    public function exists(TaskInterface $task): bool;

    /**
     * Attaches the task to a pool.
     *
     * @param TaskInterface $task
     *
     * @return self
     */
    public function attach(TaskInterface $task): ForkInterface;

    /**
     * Detaches the task from a pool.
     *
     * @param TaskInterface $task
     *
     * @return self
     */
    public function detach(TaskInterface $task): ForkInterface;

    /**
     * Return a function which executes the attached tasks.
     *
     * @return \Closure
     */
    public function each(): \Closure;

    /**
     * Execution the tasks in subprocesses.
     *
     * @param int $processesCount Number of created of subprocesses
     *
     * @return ProcessInterface
     */
    public function run(int $processesCount = ProcessInterface::AUTO_DETECT_OF_PROCESSES_QUANTITY): ProcessInterface;
}
