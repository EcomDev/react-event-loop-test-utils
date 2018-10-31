<?php
/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\ReactTestUtil;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

class LoopFactory
{
    /**
     * @var ConditionBasedLoopControlFactory
     */
    private $loopControlFactory;

    public function __construct(ConditionBasedLoopControlFactory $loopControlFactory)
    {
        $this->loopControlFactory = $loopControlFactory;
    }

    public static function create(): self
    {
        return new self(new ConditionBasedLoopControlFactory());
    }

    public function createSingleRunLoop(): LoopInterface
    {
        $loop = $this->createLoop();
        $loop->futureTick(function () use ($loop) {
            $loop->stop();
        });
        return $loop;
    }

    public function createFixedRunLoop(int $maximumRuns): LoopInterface
    {
        $counter = 0;

        $condition = function () use (&$counter, $maximumRuns) {
            $counter++;
            return $counter === $maximumRuns;
        };

        return $this->createConditionRunLoop($condition);
    }

    public function createConditionRunLoop(callable $condition): LoopInterface
    {
        $loop = $this->createLoop();
        $this->loopControlFactory->create($loop, $condition);
        return $loop;
    }

    public function createConditionRunLoopWithTimeout(callable $condition, float $timeout)
    {
        $loop = $this->createLoop();
        $this->loopControlFactory->createWithCustomTimeout($loop, $condition, $timeout);
        return $loop;
    }

    private function createLoop(): LoopInterface
    {
        return Factory::create();
    }
}
