<?php

namespace SymfonyBundles\ForkBundle\Service;

interface TaskInterface
{

    /**
     * Executes a task.
     *
     * @return void
     */
    public function execute();
}
