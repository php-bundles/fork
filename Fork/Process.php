<?php

namespace SymfonyBundles\Fork;

class Process implements ProcessInterface
{
    /**
     * @var bool
     */
    protected $allowedFork;

    /**
     * @var int
     */
    protected $processesCount;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setAllowedFork(function_exists('pcntl_fork'));
    }

    /**
     * {@inheritdoc}
     */
    public function setAllowedFork(bool $allowedFork): void
    {
        $this->allowedFork = $allowedFork;
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
        if ($this->allowedFork) {
            pcntl_wait($status);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPid(): int
    {
        return getmypid();
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function isAlive(int $pid): bool
    {
        if (0 === strncasecmp(PHP_OS, 'win', 3)) {
            exec(sprintf('TASKLIST /FO LIST /FI "PID eq %d"', $pid), $info);

            return count($info) > 1;
        }

        return posix_kill($pid, 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getMemoryUsage(): float
    {
        return round(memory_get_usage(true) / 1024 / 1024, 2);
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

        if ($this->allowedFork) {
            $this->processesCount = $processesCount;
        } else {
            $this->processesCount = 1;
        }

        return $this;
    }

    /**
     * @return bool Returns TRUE if this is a child process, else - FALSE
     */
    protected function fork(): bool
    {
        if ($this->allowedFork) {
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
        if ($this->allowedFork) {
            return posix_kill(getmypid(), SIGKILL);
        }

        return true;
    }

    /**
     * Returns the optimal number of child processes.
     *
     * @return int
     */
    protected function getOptimalNumberOfChildProcesses(): int
    {
        $coreNumber = 1;
        $detectCommand = [
            'linux' => 'cat /proc/cpuinfo | grep processor | wc -l',
        ];

        $os = strtolower(trim(PHP_OS));

        if (isset($detectCommand[$os])) {
            $coreNumber = intval($this->execute($detectCommand[$os]));
        }

        return $coreNumber;
    }

    /**
     * @param string $command
     *
     * @return string
     */
    protected function execute(string $command): string
    {
        return trim(shell_exec($command));
    }
}
