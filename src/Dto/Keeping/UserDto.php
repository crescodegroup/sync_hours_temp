<?php

declare(strict_types=1);

namespace App\Dto\Keeping;

readonly class UserDto
{
    public function __construct(
        public int $id,
        public string $first_name,
        public string $surname,
        public string $role,
        public string $state,
    ) {
    }
}
