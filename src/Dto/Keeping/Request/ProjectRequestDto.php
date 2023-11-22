<?php

declare(strict_types=1);

namespace App\Dto\Keeping\Request;

readonly class ProjectRequestDto
{
    public function __construct(
        private string $name,
        private int $client_id,
        private string $code,
        private ?string $direct = 'entire_project_is_direct',
    ) {
    }

    /**
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
