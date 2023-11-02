<?php

declare(strict_types=1);

namespace App\Dto\Keeping;

readonly class OrganisationDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $url,
    ) {
    }
}
