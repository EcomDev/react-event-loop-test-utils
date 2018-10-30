<?php
/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\ReactTestUtil;

use React\EventLoop\LoopInterface;

class ConditionBasedLoopControl
{
    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var callable
     */
    private $condition;

    /** @var float */
    private $timeStarted;
    /**
     * @var float
     */
    private $timeout;

    public function __construct(LoopInterface $loop, callable $condition, float $timeout)
    {
        $this->loop = $loop;
        $this->condition = $condition;
        $this->timeout = $timeout;
    }

    public function __invoke()
    {
        $this->startTimerWhenNotStarted();

        if ($this->isConditionMet() || $this->isTimedOut()) {
            $this->loop->stop();
            return;
        }

        $this->loop->futureTick($this);
    }

    private function startTimerWhenNotStarted(): void
    {
        if ($this->timeStarted === null) {
            $this->timeStarted = microtime(true);
        }
    }

    private function isConditionMet(): bool
    {
        return (bool)call_user_func($this->condition);
    }

    private function isTimedOut(): bool
    {
        return microtime(true) - $this->timeStarted > $this->timeout;
    }
}
