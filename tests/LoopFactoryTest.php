<?php
/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\ReactTestUtil;

use EcomDev\ReactiveSocket\React\TestReactLoopFactory;
use PHPUnit\Framework\TestCase;

class LoopFactoryTest extends TestCase
{
    /** @var LoopFactory */
    private $factory;

    protected function setUp()
    {
        $this->factory = LoopFactory::create();
    }

    /** @test */
    public function createsLoopThatRunsOnlyOnce()
    {
        $loop = $this->factory->createSingleRunLoop();
        $recorder = new TickRecorder($loop);

        $recorder->scheduleTickLabel('Tick is executed');
        $loop->run();

        $recorder->assertRecordedLabels('Tick is executed');
    }

    /**
     * @test
     * @testWith [1]
     *           [2]
     *           [3]
     *           [8]
     */
    public function createsLoopThatRunsFixedNumberOfTimesOnce(int $numberOfRuns)
    {
        $loop = $this->factory->createFixedRunLoop($numberOfRuns);
        $recorder = new TickRecorder($loop);

        $recorder->scheduleTickLabel('Loop run');
        $loop->run();


        $recorder->assertRecordedLabels(
            ...array_fill(0, $numberOfRuns, 'Loop run')
        );
    }

    /**
     * @test
     */
    public function createsLoopWithCondition()
    {
        $total = 0;
        $loop = $this->factory->createConditionRunLoop(function () use (&$total) {
            $total++;
            return $total === 3;
        });

        $recorder = new TickRecorder($loop);

        $recorder->scheduleTickLabel('Condition not met');
        $loop->run();


        $recorder->assertRecordedLabels(
            'Condition not met',
            'Condition not met',
            'Condition not met'
        );
    }
}
