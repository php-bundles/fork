<?php

namespace SymfonyBundles\ForkBundle\Service;

class Process implements ProcessInterface
{

    /**
     * @var int
     */
    protected $size = ProcessInterface::DEFAULT_QUANTITY_PROCESESS;

    /**
     * {@inheritdoc}
     */
    public function size($size)
    {
        if ($size < 1) {
            $size = 1;
        }

        if ($size > static::MAX_QUANTITY_PROCESESS) {
            $size = static::MAX_QUANTITY_PROCESESS;
        }

        $this->size = (int) $size;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function create(\Closure $closure)
    {
        for ($i = 0; $i < $this->size; $i++) {
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
    public function wait()
    {
        while ($pid = pcntl_waitpid(-1, $status)) {
            if ($pid === -1) {
                break;
            }
        }

        return $this;
    }

    /**
     * @return bool Returns TRUE if this is a child process, else - FALSE.
     */
    protected function fork()
    {
        return 0 === pcntl_fork();
    }

    /**
     * @codeCoverageIgnore
     *
     * @return bool
     */
    public function terminate()
    {
        return posix_kill(getmypid(), SIGKILL);
    }

}
