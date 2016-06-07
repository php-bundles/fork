<?php

namespace SymfonyBundles\ForkBundle\Service;

interface ForkInterface
{

    /**
     * Checks the availability of task in a pool.
     *
     * @param TaskInterface $task
     *
     * @return bool Returns TRUE if exist, else FALSE.
     */
    public function exists(TaskInterface $task);

    /**
     * Attaches the task to a pool.
     *
     * @param TaskInterface $task
     *
     * @return ForkInterface
     */
    public function attach(TaskInterface $task);

    /**
     * Detaches the task from a pool.
     *
     * @param TaskInterface $task
     *
     * @return ForkInterface
     */
    public function detach(TaskInterface $task);

    /**
     * Return a function which executes the attached tasks.
     *
     * @return \Closure
     */
    public function each();

    /**
     * Execution the tasks in subprocesses.
     *
     * @param int $size Number of created of subprocesses.
     *
     * @return ProcessInterface
     */
    public function run($size = ProcessInterface::DEFAULT_QUANTITY_PROCESESS);
}
