<?php
/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\ReactTestUtil;

use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

class ConditionBasedLoopControlTest extends TestCase
{
    /** @var LoopInterface */
    private $loop;

    /** @var ConditionBasedLoopControlFactory */
    private $factory;

    /** @var TickRecorder */
    private $recorder;

    protected function setUp()
    {
        $this->loop = Factory::create();
        $this->factory = new ConditionBasedLoopControlFactory();
        $this->recorder = new TickRecorder($this->loop);
    }

    /** @test */
    public function stopsLoopAfterFirstTimeConditionIsMet()
    {
        $this->recorder->scheduleTickLabel('Tick executed');

        $this->factory->create($this->loop, function () {
            return !$this->recorder->isEmptyRecordList();
        });

        $this->loop->run();

        $this->recorder->assertRecordedLabels('Tick executed');
    }

    /** @test */
    public function stopsLoopAfterSecondWhenConditionIsNotMet()
    {
        $this->recorder->scheduleEveryTick(function () {
            usleep(200000);
        });

        $this->recorder->scheduleTickLabel('Every 200ms');

        $this->factory->create(
            $this->loop,
            function () {
                return false;
            }
        );

        $this->loop->run();

        $this->recorder->assertRecordedLabels(
            'Every 200ms',
            'Every 200ms',
            'Every 200ms',
            'Every 200ms',
            'Every 200ms',
            'Every 200ms'
        );
    }
    
    /** @test */
    public function allowsToSpecifyCustomTimeoutForNotMetCondition()
    {
        $this->recorder->scheduleEveryTick(function () {
            usleep(200000);
        });

        $this->recorder->scheduleTickLabel('Every 200ms');

        $this->factory->createWithCustomTimeout(
            $this->loop,
            function () {
                return false;
            },
            0.5
        );

        $this->loop->run();

        $this->recorder->assertRecordedLabels(
            'Every 200ms',
            'Every 200ms',
            'Every 200ms',
            'Every 200ms'
        );
    }

    private function scheduleEveryTick(callable $recorder)
    {
        $this->loop->futureTick(function () use ($recorder) {
            $recorder();
            $this->scheduleEveryTick($recorder);
        });
    }
}
