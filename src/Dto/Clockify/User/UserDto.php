<?php

declare(strict_types=1);

namespace App\Dto\Clockify\User;

readonly class UserDto
{
    public function __construct(
        public string $id,
        public string $email,
        public string $name,
        public array $memberships,
        public string $profilePicture,
        public string $activeWorkspace,
        public string $defaultWorkspace,
    ) {
    }
}
