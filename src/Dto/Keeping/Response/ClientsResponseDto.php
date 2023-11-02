<?php

declare(strict_types=1);

namespace App\Dto\Keeping\Response;

use App\Dto\Keeping\ClientDto;

readonly class ClientsResponseDto
{
    /**
     * @param ClientDto[] $clients
     */
    public function __construct(
        public array $clients
    ) {
    }
}
