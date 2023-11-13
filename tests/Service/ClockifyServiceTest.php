<?php

use App\Dto\Clockify\TimeEntry\TimeEntryDto;
use App\Dto\Clockify\User\UserDto;
use App\Service\ClockifyService;
use App\Service\SyncService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * ClockifyServiceTest
 * @group clockify
 */
class ClockifyServiceTest extends KernelTestCase
{   
    /** @test */
    public function test_getTimeEntriesPreviousMonth()
    {
        self::bootKernel();
        $container = static::getContainer();

        $syncService = $container->get(ClockifyService::class);
        $timeEntries = $syncService->getTimeEntriesPreviousMonth();

        $this->assertIsArray($timeEntries);
        $this->assertContainsOnlyInstancesOf(TimeEntryDto::class, $timeEntries);
    }
}
