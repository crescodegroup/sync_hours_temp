<?php

declare(strict_types=1);

namespace App\Dto\Keeping\Response;

use App\Dto\Keeping\TaskDto;

readonly class TasksResponseDto
{
    /**
     * @param TaskDto[] $tasks
     */
    public function __construct(
        public array $tasks
    ) {
    }
}
