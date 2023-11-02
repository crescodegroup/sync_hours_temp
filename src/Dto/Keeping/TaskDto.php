<?php

declare(strict_types=1);

namespace App\Dto\Keeping;

readonly class TaskDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $code,
        public ?bool $direct,
        public string $state,
    ) {
    }
}
