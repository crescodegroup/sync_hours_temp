<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\ClockifyService;
use App\Dto\Clockify\TimeEntry\TimeEntryDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * ClockifyServiceTest
 * @group clockify
 */
class ClockifyServiceTest extends KernelTestCase
{
    /** @test */
    public function testGetTimeEntriesPreviousMonth()
    {
        self::bootKernel();
        $container = static::getContainer();

        $syncService = $container->get(ClockifyService::class);
        $timeEntries = $syncService->getTimeEntriesPreviousMonth();

        $this->assertIsArray($timeEntries);
        $this->assertContainsOnlyInstancesOf(TimeEntryDto::class, $timeEntries);
    }
}
