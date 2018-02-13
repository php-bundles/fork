<?php

namespace SymfonyBundles\Fork;

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
    public function __construct(ProcessInterface $process = null)
    {
        if (null === $process) {
            $process = new Process();
        }

        $this->tasks = [];
        $this->process = $process;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcess(): ProcessInterface
    {
        return $this->process;
    }

    /**
     * {@inheritdoc}
     */
    public function exists(TaskInterface $task): bool
    {
        return false !== in_array($task, $this->tasks, true);
    }

    /**
     * {@inheritdoc}
     */
    public function attach(TaskInterface $task): ForkInterface
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function detach(TaskInterface $task): ForkInterface
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
    public function each(): \Closure
    {
        return function () {
            foreach ($this->tasks as $task) {
                $task->execute($this->process);
            }
        };
    }

    /**
     * {@inheritdoc}
     */
    public function run(int $processesCount = ProcessInterface::AUTO_DETECT_OF_PROCESSES_QUANTITY): ProcessInterface
    {
        return $this->process->setCountOfChildProcesses($processesCount)->create($this->each());
    }
}
