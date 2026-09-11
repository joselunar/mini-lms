<?php

use App\Domain\ProgressCalculator;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ProgressCalculatorTest extends CIUnitTestCase
{
    public function testPercentRoundsDown(): void
    {
        $calc = new ProgressCalculator();

        $this->assertSame(0, $calc->percent(0, 3));
        $this->assertSame(33, $calc->percent(1, 3));
        $this->assertSame(66, $calc->percent(2, 3));
        $this->assertSame(100, $calc->percent(3, 3));
    }

    public function testEmptyCourseHasZeroProgress(): void
    {
        $calc = new ProgressCalculator();

        $this->assertSame(0, $calc->percent(0, 0));
        $this->assertFalse($calc->isCourseComplete(0, 0));
    }

    public function testCourseCompletesOnlyWhenAllLessonsAreDone(): void
    {
        $calc = new ProgressCalculator();

        $this->assertFalse($calc->isCourseComplete(2, 3));
        $this->assertTrue($calc->isCourseComplete(3, 3));
    }
}
