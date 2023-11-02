<?php

declare(strict_types=1);

namespace App\Dto\Keeping\Response;

use App\Dto\Keeping\ProjectDto;

readonly class ProjectsResponseDto
{
    /**
     * @param ProjectDto[] $projects
     */
    public function __construct(
        public array $projects
    ) {
    }
}
