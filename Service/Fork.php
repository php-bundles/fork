<?php

namespace SymfonyBundles\ForkBundle\Service;

class Fork implements ForkInterface
{

    /**
     * @var array
     */
    protected $tasks;

    /**
     * @var ProcessInterface
     */
    protected $process;

    /**
     * @param ProcessInterface $process
     */
    public function __construct(ProcessInterface $process)
    {
        $this->tasks = [];
        $this->process = $process;
    }

    /**
     * {@inheritdoc}
     */
    public function exists(TaskInterface $task)
    {
        return false !== in_array($task, $this->tasks, true);
    }

    /**
     * {@inheritdoc}
     */
    public function attach(TaskInterface $task)
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function detach(TaskInterface $task)
    {
        if ($this->exists($task)) {
            $key = array_search($task, $this->tasks, true);

            unset($this->tasks[$key]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function each()
    {
        return function() {
            foreach ($this->tasks as $i => $task) {
                $task->execute($i);
            }
        };
    }

    /**
     * {@inheritdoc}
     */
    public function run($size = ProcessInterface::DEFAULT_QUANTITY_PROCESESS)
    {
        return $this->process->size($size)->create($this->each());
    }

}
