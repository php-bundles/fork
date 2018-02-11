<?php

namespace SymfonyBundles\ForkBundle\Fork;

interface TaskInterface
{
    /**
     * Executes a task.
     */
    public function execute(): void;
}
