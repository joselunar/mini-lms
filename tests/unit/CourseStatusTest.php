<?php

use App\Domain\CourseStatus;
use App\Domain\DomainException;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class CourseStatusTest extends CIUnitTestCase
{
    public function testPublishedIsVisibleAndAllowsEnrollment(): void
    {
        $status = new CourseStatus(CourseStatus::PUBLISHED);

        $this->assertTrue($status->isVisibleInCatalog());
        $this->assertTrue($status->canEnroll());
        $this->assertTrue($status->canReceiveProgress());
        $this->assertTrue($status->canClose());
        $this->assertFalse($status->canPublish());
    }

    public function testDraftIsHiddenAndBlocksEnrollment(): void
    {
        $status = new CourseStatus(CourseStatus::DRAFT);

        $this->assertFalse($status->isVisibleInCatalog());
        $this->assertFalse($status->canEnroll());
        $this->assertFalse($status->canReceiveProgress());
        $this->assertTrue($status->canPublish());
    }

    public function testClosedBlocksNewEnrollmentButKeepsProgress(): void
    {
        $status = new CourseStatus(CourseStatus::CLOSED);

        $this->assertFalse($status->canEnroll());
        $this->assertTrue($status->canReceiveProgress());
        $this->assertFalse($status->isVisibleInCatalog());
    }

    public function testInvalidStatusIsRejected(): void
    {
        $this->expectException(DomainException::class);
        new CourseStatus('publicado-errado');
    }
}
