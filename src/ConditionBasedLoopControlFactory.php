<?php
/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\ReactTestUtil;

use React\EventLoop\LoopInterface;

class ConditionBasedLoopControlFactory
{
    /** Default timeout for loop stop */
    private const DEFAULT_TIMEOUT = 1.0;

    public function create(LoopInterface $loop, callable $condition): ConditionBasedLoopControl
    {
        $timeout = self::DEFAULT_TIMEOUT;

        return $this->createControlAndScheduleItOnNextTick($loop, $condition, $timeout);
    }

    public function createWithCustomTimeout(
        LoopInterface $loop,
        callable $condition,
        float $timeout
    ): ConditionBasedLoopControl {
        return $this->createControlAndScheduleItOnNextTick($loop, $condition, $timeout);
    }

    private function createControlAndScheduleItOnNextTick(
        LoopInterface $loop,
        callable $condition,
        float $timeout
    ): ConditionBasedLoopControl {
        $loopControl = new ConditionBasedLoopControl($loop, $condition, $timeout);
        $loop->futureTick($loopControl);

        return $loopControl;
    }
}
