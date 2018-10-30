<?php
/**
 * Copyright © EcomDev B.V. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace EcomDev\ReactTestUtil;

use PHPUnit\Framework\Assert;
use React\EventLoop\LoopInterface;

class TickRecorder
{
    private $reportedLabels = [];

    /**
     * @var LoopInterface
     */
    private $loop;

    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    public function scheduleTickLabel(string $label): void
    {
        $this->scheduleEveryTick(function () use ($label) {
            $this->reportedLabels[] = $label;
        });
    }

    public function scheduleEveryTick(callable $recorder): void
    {
        $this->loop->futureTick(function () use ($recorder) {
            $recorder();
            $this->scheduleEveryTick($recorder);
        });
    }

    public function assertRecordedLabels(string... $expectedLabels)
    {
        Assert::assertEquals($expectedLabels, $this->reportedLabels);
    }

    public function isEmptyRecordList(): bool
    {
        return empty($this->reportedLabels);
    }
}
