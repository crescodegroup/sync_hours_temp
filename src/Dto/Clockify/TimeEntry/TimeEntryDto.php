<?php

declare(strict_types=1);

namespace App\Dto\Clockify\TimeEntry;

readonly class TimeEntryDto
{
    public function __construct(
        public string $id,
        public string $description,
        public array $tagIds,
        public string $userId,
        public bool $billable,
        public ?string $taskId,
        public string $projectId,
        public string $workspaceId,
        public TimeIntervalDto $timeInterval,
        public array $customFieldValues,
        public string $type,
        public ?string $kioskId,
        public ?string $hourlyRate,
        public ?string $costRate,
        public bool $isLocked,
    ) {
    }
}
