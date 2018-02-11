<?php

namespace SymfonyBundles\Fork;

class Process implements ProcessInterface
{
    /**
     * @var bool
     */
    protected $isAllowedFork;

    /**
     * @var int
     */
    protected $processesCount;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->isAllowedFork = function_exists('pcntl_fork');
    }

    /**
     * {@inheritdoc}
     */
    public function setCountOfChildProcesses(int $processesCount): ProcessInterface
    {
        if ($processesCount < 1) {
            $processesCount = $this->getOptimalNumberOfChildProcesses();
        }

        if ($processesCount > static::MAX_PROCESSES_QUANTITY) {
            $processesCount = static::MAX_PROCESSES_QUANTITY;
        }

        if ($this->isAllowedFork) {
            $this->processesCount = $processesCount;
        } else {
            $this->processesCount = 1;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function create(\Closure $closure): ProcessInterface
    {
        for ($i = 0; $i < $this->processesCount; ++$i) {
            if ($this->fork()) {
                $closure();

                $this->terminate();
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function wait(): ProcessInterface
    {
        if ($this->isAllowedFork) {
            pcntl_wait($status);
        }

        return $this;
    }

    /**
     * @return bool Returns TRUE if this is a child process, else - FALSE
     */
    protected function fork(): bool
    {
        if ($this->isAllowedFork) {
            return 0 === pcntl_fork();
        }

        return true;
    }

    /**
     * @codeCoverageIgnore
     *
     * @return bool
     */
    protected function terminate(): bool
    {
        if ($this->isAllowedFork) {
            return posix_kill(getmypid(), SIGKILL);
        }

        return true;
    }

    /**
     * Returns the optimal number of child processes.
     *
     * @codeCoverageIgnore
     *
     * @return int
     */
    protected function getOptimalNumberOfChildProcesses(): int
    {
        $coreNumber = 1;
        $detectCommand = [
            'linux' => 'cat /proc/cpuinfo | grep processor | wc -l',
            'freebsd' => 'sysctl -a | grep "hw.ncpu" | cut -d ":" -f2',
        ];

        $os = strtolower(trim(PHP_OS));

        if (isset($detectCommand[$os])) {
            $coreNumber = intval(trim(shell_exec($detectCommand[$os])));
        }

        return $coreNumber;
    }
}
