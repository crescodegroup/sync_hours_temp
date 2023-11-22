<?php

declare(strict_types=1);

namespace App\Dto\Keeping\Response;

use App\Dto\Keeping\ProjectDto;

readonly class ProjectResponseDto
{
    /**
     * @param ProjectDto $project
     */
    public function __construct(
        public ProjectDto $project
    ) {
    }
}
