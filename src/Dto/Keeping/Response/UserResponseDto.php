<?php

declare(strict_types=1);

namespace App\Dto\Keeping\Response;

use App\Dto\Keeping\UserDto;

readonly class UserResponseDto
{
    public function __construct(
        public UserDto $user
    ) {
    }
}
