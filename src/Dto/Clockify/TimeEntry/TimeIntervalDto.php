<?php

declare(strict_types=1);

namespace App\Dto\Clockify\TimeEntry;

use DateInterval;
use DateTimeImmutable;

readonly class TimeIntervalDto
{
    public function __construct(
        public DateTimeImmutable $start,
        public DateTimeImmutable $end,
        public DateInterval $duration,
    ) {
    }
}
