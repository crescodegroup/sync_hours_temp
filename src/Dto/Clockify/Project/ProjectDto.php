<?php

declare(strict_types=1);

namespace App\Dto\Clockify\Project;

readonly class ProjectDto
{
    public function __construct(
        public string $id,
        public string $clientId,
        public string $workspaceId,
        public string $name,
    ) {
    }
}
